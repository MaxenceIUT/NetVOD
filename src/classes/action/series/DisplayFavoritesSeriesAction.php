<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Series;
use iutnc\netvod\db\ConnectionFactory;

class DisplayFavoritesSeriesAction extends Action
{
    public function execute(): string
    {
        $html = "Vos séries préférées: ";
        $pdo = ConnectionFactory::getConnection();
        $query = "select id from favorite_series where email = :email";
        $statement = $pdo->prepare($query);
        $email = Auth::getCurrentUser()->email;
        $statement->bindParam(":email", $email);
        $statement->execute();
        $result = $statement->fetchAll();
        $user = $_SESSION['user'];
        foreach ($result as $serieN) {
            $serie = Series::find($serieN['id']);
            $fav = $user->hasFavorite($serie->id);
            $html .= "<li><a href='index.php?action=show-series-details&id=" . $serie->id . "&fav=" . $fav . ">" . $serie->titre . "</a></li>";
        }
        return $html;
    }

    public function getActionName(): string
    {

        return "display-favorites-series";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}