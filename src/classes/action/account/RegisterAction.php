<?php

namespace iutnc\netvod\action\account;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\exceptions\RegisterException;

class RegisterAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            $html = <<<END
            <form action="index.php?action=register" method="post">
                <label for="first-name">Prénom</label>
                <input type="text" name="first-name" id="first-name" required>
                <label for="last-name">Nom</label>
                <input type="text" name="last-name" id="last-name" required>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required minlength="8" maxlength="128">
                <label for="password-repeat">Répéter le mot de passe</label>
                <input type="password" name="password-repeat" id="password-repeat" required minlength="8" maxlength="128">
                <input type="submit" value="S'inscrire">
            </form>
            END;
            return $html;
        } else if ($this->http_method == "POST") {
            $firstName = $_POST['first-name'];
            $lastName = $_POST['last-name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordRepeat = $_POST['password-repeat'];
            if ($password == $passwordRepeat) {
                try {
                    Auth::register($firstName, $lastName, $email, $password);
                    return "Inscription réussie !";
                } catch (RegisterException $e) {
                    return "Erreur lors de l'inscription : " . $e->getMessage();
                }
            } else {
                return "Les mots de passe ne correspondent pas";
            }
        } else {
            return "Méthode non autorisée";
        }
    }

    public function getActionName(): string
    {
        return "register";
    }

    public function shouldBeAuthenticated(): bool
    {
        return false;
    }

}