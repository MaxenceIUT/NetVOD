<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;

class ShowSeriesDetailsAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $pdo = ConnectionFactory::getConnection();
        $querySeries = "SELECT * FROM serie WHERE id = :id";
        $statement = $pdo->prepare($querySeries);
        $statement->bindParam(":id", $_GET['id']);
        $statement->execute();
        $serie = $statement->fetchAll();
        $html .= "<h1>{$serie['titre']}</h1>";
        $html .= "<p>Résumé: {$serie['descriptif']}</p>";
        $html .= "<p>Année: {$serie['annee']}</p>";
        $queryEpisodes = "SELECT * FROM episode WHERE serie_id = :id";
        $statement = $pdo->prepare($queryEpisodes);
        $statement->bindParam(":id", $_GET['id']);
        $statement->execute();
        $episodes = $statement->fetchAll();
        $nbepisodes = count($episodes);
        $html .= "<p>$nbepisodes épisodes</p>";
        foreach ($episodes as $episode) {
            $html .= "<p>Episode {$episode['numero']}</p>";
            $html .= "<p>{$episode['titre']}</p>";
            $html .= "<p>{$episode['resume']} minutes</p>";
            $html .= "<p>{$episode['duree']}</p>";
        }
        return $html;
        // TODO: Implement execute() method.
    }
}