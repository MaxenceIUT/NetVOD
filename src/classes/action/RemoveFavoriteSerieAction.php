<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Series;
use iutnc\netvod\db\ConnectionFactory;

class RemoveFavoriteSerieAction extends Action
{

    public function execute(): string
    {
        $pdo = ConnectionFactory::getConnection();
        $id = $_GET['id'];
        $email = Auth::getCurrentUser()->email;

        $series = Series::find($_GET['id']);
        if (!$series->isBookmarkedBy(Auth::getCurrentUser())) return "Cette série n'est pas dans vos favoris";

        $query = "delete from bookmarked_series where email = ? and id = ?";
        $statement = $pdo->prepare($query);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $id);

        $statement->execute();

        return "Vous avez supprimé cette série de vos favoris";
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