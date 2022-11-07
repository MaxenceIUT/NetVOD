<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\users\User;

class Auth
{

    public static function authenticate($email, $password): ?User
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $statement->bindParam(":email", $email);

        if ($statement->execute()) {
            $user = $statement->fetchObject(User::class);
            if ($user && password_verify($password, $user->passwd)) {
                $_SESSION["user"] = $user;
                return $user;
            }
        }
        return null;
    }

    public static function register($email, $password, $role): bool
    {
        $pdo = ConnectionFactory::getConnection();
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $role = filter_var($role, FILTER_VALIDATE_INT);
        if (strlen($password) >= 8 && $password = password_hash($password, PASSWORD_DEFAULT)) {
            $statement = $pdo->prepare("INSERT INTO user (email, passwd, role) VALUES (:email, :passwd, :role)");
            $statement->bindParam(":email", $email);
            $statement->bindParam(":passwd", $password);
            $statement->bindParam(":role", $role);
            return $statement->execute();
        }
        return false;
    }

}