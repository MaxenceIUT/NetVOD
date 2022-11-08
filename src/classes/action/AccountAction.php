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
            <h2>Gestion de mon compte</h2>
            <form action="index.php?action=account" method="post">
                <label for="first-name">Prénom</label>
                <input type="text" name="first-name" id="first-name" placeholder="Prénom" value="$user->first_name" required>
                <label for="last-name">Nom</label>
                <input type="text" name="last-name" id="last-name" placeholder="Nom" value="$user->last_name" required>
                <input type="submit" value="Mettre à jour">
            </form>
            END;

            return $html;
        } else if ($this->http_method == "POST") {
            $user = $_SESSION['user'];
            $_POST['first-name'] = filter_var($_POST['first-name'], FILTER_SANITIZE_STRING);
            $_POST['last-name'] = filter_var($_POST['last-name'], FILTER_SANITIZE_STRING);

            $user->first_name = $_POST['first-name'];
            $user->last_name = $_POST['last-name'];
            if ($user->save()) {
                $_SESSION['user'] = $user;
                return "Compte mis à jour";
            } else {
                return "Erreur lors de la mise à jour";
            }
        } else {
            return "Méthode non autorisée";
        }
    }

    public function getActionName(): string
    {
        return "account";
    }
}