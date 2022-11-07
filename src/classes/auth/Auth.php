<?php

namespace iutnc\netvod\auth;

use iutnc\netvod\db\ConnectionFactory;
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

    public static function register($firstName, $lastName, $email, $password): bool
    {
        $pdo = ConnectionFactory::getConnection();
        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (strlen($password) >= 8 && strlen($password) <= 128 && $password = password_hash($password, PASSWORD_DEFAULT)) {
            $statement = $pdo->prepare("INSERT INTO users (email, password, last_name, first_name) VALUES (:email, :password, :last_name, :first_name)");
            $statement->bindParam(":email", $email);
            $statement->bindParam(":password", $password);
            $statement->bindParam(":last_name", $lastName);
            $statement->bindParam(":first_name", $firstName);
            return $statement->execute();
        }
        return false;
    }

}