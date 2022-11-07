<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;

class LoginAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            if (isset($_SESSION['user'])) {
                header('Location: index.php?action=account');
            } else {
                $html = <<<END
                <form action="index.php?action=login" method="post">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required minlength="8" maxlength="128">
                    <input type="submit" value="Se connecter">
                </form>
                END;
                return $html;
            }
        } else if ($this->http_method == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = Auth::authenticate($email, $password);
            if ($user == null) {
                return "Utilisateur inconnu";
            } else {
                $_SESSION['user'] = $user;
                header("Location: index.php?action=account");
                return "Connexion réussie";
            }
        } else {
            return "Méthode non autorisée";
        }
    }

    public function getActionName(): string
    {
        return "login";
    }

}