<?php

namespace iutnc\netvod\action\account;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;

class LogoutAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            if (Auth::getCurrentUser() != null) {
                session_destroy();
                header("Location: index.php");
            } else {
                return "Vous n'êtes pas connecté";
            }
        } else {
            return "Méthode non autorisée";
        }
    }

    public function getActionName(): string
    {
        return "logout";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}