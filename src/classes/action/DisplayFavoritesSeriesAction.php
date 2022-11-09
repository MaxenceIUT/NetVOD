<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\lists\Serie;

class DisplayFavoritesSeriesAction extends Action
{
    public function execute(): string
    {
        $html = "Vos séries préférées: ";
        $pdo = ConnectionFactory::getConnection();
        $query = "select id from favorite_series where email = :email";
        $statement = $pdo->prepare($query);
        $email = $_SESSION['user']->email;
        $statement->bindParam(":email", $email);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $serieN) {
            $serie = Serie::find($serieN['id']);
            $html .= "<li><a href='index.php?action=show-series-details&id=" . $serie->id . "'>" . $serie->titre . "</a></li>";
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