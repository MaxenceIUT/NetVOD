<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;

class AddFavoriteSerieAction extends Action
{


    public function execute(): string
    {
        $html = "";
        $pdo = ConnectionFactory::getConnection();
        $query = "insert into favorite_series (email, id) values (:email, :id)";
        $statement = $pdo->prepare($query);
        $user_email = $_SESSION['user']->email;
        $statement->bindParam(":email", $user_email);
        $id = $_GET['id'];
        $statement->bindParam(":id", $id);
        $statement->execute();
        $html .= "Série présente dans les favoris";
        return $html;
    }

    public function getActionName(): string
    {
        return "add-favorite-serie";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}