<?php

namespace iutnc\netvod\action;

class AccountAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            $user = $_SESSION['user'];

            $html = <<<END
            <h1>Bonjour $user->first_name 👋</h1>
            <a href="index.php?action=logout">Déconnexion</a>
            END;

            return $html;
        } else {
            return "Méthode non autorisée";
        }
    }

}