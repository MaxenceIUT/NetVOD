<?php

namespace iutnc\netvod\auth;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exceptions\RegisterException;
use iutnc\netvod\users\User;

class Auth
{

    public static function authenticate($email, $password): ?User
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindParam(":email", $email);

        if ($statement->execute()) {
            $user = $statement->fetchObject(User::class);
            if ($user && password_verify($password, $user->password)) {
                $_SESSION["user"] = $user;
                return $user;
            }
        }
        return null;
    }

    /**
     * @return User|null The user if authenticated, null otherwise
     */
    public static function getCurrentUser(): ?User
    {
        return $_SESSION["user"] ?? null;
    }

    /**
     * @throws RegisterException
     */
    public static function register($firstName, $lastName, $email, $password)
    {
        $pdo = ConnectionFactory::getConnection();

        // Filtering user input
        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Check if email is already used
        $query = $pdo->prepare("SELECT email FROM users WHERE email = :email");
        $query->execute(['email' => $email]);

        if ($query->rowCount() > 0) throw new RegisterException("Email déjà utilisé");

        // Checking password length
        if (strlen($password) < 8 || strlen($password) > 128) throw new RegisterException("Le mot de passe doit faire entre 8 et 128 caractères");

        // Hashing password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Inserting user in database
        $statement = $pdo->prepare("INSERT INTO users (email, password, last_name, first_name) VALUES (:email, :password, :last_name, :first_name)");
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":last_name", $lastName);
        $statement->bindParam(":first_name", $firstName);
        if (!$statement->execute()) throw new RegisterException("Une erreur est survenue lors de l'inscription");
    }

}