<?php

namespace iutnc\netvod\auth;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exceptions\LoginException;
use iutnc\netvod\exceptions\RegisterException;
use iutnc\netvod\users\User;

class Auth
{

    /**
     * @throws LoginException
     */
    public static function authenticate($email, $password): ?User
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindParam(":email", $email);

        if ($statement->execute()) {
            $user = $statement->fetchObject(User::class);
            if ($user && password_verify($password, $user->password)) {
                if ($user->activated) {
                    $_SESSION["user"] = $user;
                    return $user;
                } else {
                    throw new LoginException("Compte non activé, veuillez vérifier vos mails");
                }
            } else {
                throw new LoginException("Email ou mot de passe incorrect");
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
     * @param $firstName string The user first name
     * @param $lastName string The user last name
     * @param $email string The user email
     * @param $password string The user password
     * @param $repeatPassword string The user password repeated
     * @return string The activation link
     * @throws RegisterException If an exception occurs during the registration
     */
    public static function register($firstName, $lastName, $email, $password, $repeatPassword): string
    {
        if ($password != $repeatPassword) throw new RegisterException("Les mots de passe ne correspondent pas");

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

        $user = User::find($email);
        $activationToken = $user->generateActivationToken();
        return "index.php?action=activate-account&email=$email&token=$activationToken";
    }

    public static function changePassword($email, $password)
    {
        $pdo = ConnectionFactory::getConnection();
        $sql = "UPDATE users SET password = :password WHERE email = :email";
        $statement = $pdo->prepare($sql);

        $hash = self::verifPassword($password);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $hash);
        $statement->execute();

    }

    public static function verifPassword($password): string
    {
        if (strlen($password) < 8 || strlen($password) > 128) throw new RegisterException("Le mot de passe doit faire entre 8 et 128 caractères");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;

    }

}