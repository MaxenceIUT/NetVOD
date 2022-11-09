<?php

namespace iutnc\netvod\action;

class LogoutAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            if (isset($_SESSION['user'])) {
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