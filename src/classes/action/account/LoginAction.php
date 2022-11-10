<?php

namespace iutnc\netvod\action\account;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\exceptions\LoginException;

class LoginAction extends Action
{

    private string $loginForm;

    public function __construct()
    {
        parent::__construct();
        $this->loginForm = <<<END
        <div class="login">
            <h1>Connexion</h1>
            <form action="index.php?action=login" method="post">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="example@domain.tld" required>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Mot de passe" required minlength="8" maxlength="128">
                <a href="index.php?action=password-forget">Mot de passe oublié ?</a>
                <input class="button-link button-link__plain" type="submit" value="Se connecter">
            </form>
        </div>
        END;
    }

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            if (Auth::getCurrentUser() != null) {
                header('Location: index.php?action=home');
            } else {
                return $this->loginForm;
            }
        } else if ($this->http_method == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];
            try {
                Auth::authenticate($email, $password);
                header("Location: index.php?action=home");
                return "Connexion réussie";
            } catch (LoginException $e) {
                $errorMessage = $e->getMessage();
                return <<<END
                <div class="error">
                    Une erreur est survenue lors de la connexion, veuillez réessayer.<br>                
                    $errorMessage
                </div>
                $this->loginForm
                END;
                $html .= $e->getMessage();
                return $html;
            }
        } else {
            return "Méthode non autorisée";
        }
        return "";
    }

    public function getActionName(): string
    {
        return "login";
    }

    public function shouldBeAuthenticated(): bool
    {
        return false;
    }

}