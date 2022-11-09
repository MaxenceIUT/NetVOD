<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;

class RemoveFavoriteSerieAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $pdo = ConnectionFactory::getConnection();
        $query = "delete from favorite_series where email = :email and id = :id";
        $statement = $pdo->prepare($query);
        $user_email = $_SESSION['user']->email;
        $statement->bindParam(":email", $user_email);
        $id = $_GET['id'];
        $statement->bindParam(":id", $id);
        $statement->execute();
        $html .= "Série absente des  favoris";
        return $html;
    }

    public function getActionName(): string
    {
        return "remove-favorite-serie";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}