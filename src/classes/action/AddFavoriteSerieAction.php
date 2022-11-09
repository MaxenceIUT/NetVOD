<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Series;
use iutnc\netvod\db\ConnectionFactory;

class AddFavoriteSerieAction extends Action
{

    public function execute(): string
    {
        $pdo = ConnectionFactory::getConnection();
        $id = $_GET['id'];
        $series = Series::find($id);
        $email = Auth::getCurrentUser()->email;

        if ($series->isBookmarkedBy(Auth::getCurrentUser())) return "Cette série est déjà dans vos favoris";

        $query = "insert into bookmarked_series (email, id) values (:email, :id)";
        $statement = $pdo->prepare($query);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":id", $id);

        $statement->execute();

        return "Vous avez ajouté cette série à vos favoris";
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