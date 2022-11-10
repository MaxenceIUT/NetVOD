<?php

namespace iutnc\netvod\action\account;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\db\ConnectionFactory;
use PDO;

class AccountAction extends Action
{

    private string $form;

    private function generateForm()
    {
        $user = Auth::getCurrentUser();

        $this->form = <<<END
        <h1>Gestion de mon compte</h1>
        <form action="index.php?action=account" method="post">
            <label for="first-name">Prénom</label>
            <input type="text" name="first-name" id="first-name" placeholder="Prénom" value="$user->first_name" required>
            <label for="last-name">Nom</label>
            <input type="text" name="last-name" id="last-name" placeholder="Nom" value="$user->last_name" required>
            <label for="favorite-genre">Genre préféré</label>
        END;

        $genres = $this->getAvailableGenres();
        $this->form .= "<select name=\"favorite-genre\" id=\"favorite-genre\">";

        $selected = $user->favorite_genre == null ? "selected" : "";
        $this->form .= "<option value=\"\" $selected>Ne pas spécifier</option>";

        foreach ($genres as $genre) {
            $selected = $genre['id'] == $user->favorite_genre ? "selected" : "";
            $this->form .= "<option value=\"{$genre['id']}\" $selected>{$genre['genre']}</option>";
        }

        $this->form .= <<<END
                </select>
                <input type="submit" value="Mettre à jour">
            </form>
            END;
    }

    public function execute(): string
    {
        $this->generateForm();
        if ($this->http_method == "GET") {
            return $this->form;
        } else if ($this->http_method == "POST") {
            $user = Auth::getCurrentUser();

            $_POST['first-name'] = filter_var($_POST['first-name'], FILTER_SANITIZE_STRING);
            $_POST['last-name'] = filter_var($_POST['last-name'], FILTER_SANITIZE_STRING);
            $_POST['favorite-genre'] = filter_var($_POST['favorite-genre'], FILTER_SANITIZE_STRING);
            $user->first_name = $_POST['first-name'];
            $user->last_name = $_POST['last-name'];
            $user->favorite_genre = $_POST['favorite-genre'] == "" ? null : $_POST['favorite-genre'];

            if ($user->save()) {
                return "Compte mis à jour" . $this->form;
            } else {
                return "Erreur lors de la mise à jour" . $this->form;
            }
        } else {
            return "Méthode non autorisée";
        }
    }

    public function getAvailableGenres(): array
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select id, genre from genres";
        $statement = $pdo->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActionName(): string
    {
        return "account";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }

}