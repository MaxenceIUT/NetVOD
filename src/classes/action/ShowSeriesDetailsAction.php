<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\lists\Serie;

class ShowSeriesDetailsAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $pdo = ConnectionFactory::getConnection();
        $serie = new Serie($_GET['id']);
        $html .= "<h1>Titre:" . $serie->titre . "</h1>";
        $html .= "<img src='assets/img/" . $serie->img . "' alt=''>";
        $html .= "<p>Résumé:" . $serie->descriptif . "</p>";
        $html .= "<p>Année: " . $serie->annee . "</p>";
        $html .= "<p>Date d'ajout: " . $serie->date_ajout . "</p>";
        $idS = $serie->id;
        $queryEpisodes = "SELECT * FROM episode WHERE serie_id = :id";
        $statement = $pdo->prepare($queryEpisodes);
        $statement->bindParam(":id", $idS);
        $statement->execute();
        $episodes = $statement->fetchAll();
        $nbepisodes = count($episodes);
        $html .= "<p>" . $nbepisodes . " épisodes</p>";
        foreach ($episodes as $episode) {
            $html .= "<p>Episode" . $episode['numero'] . "</p>";
            $html .= "<p>" . $episode['titre'] . "</p>";
            $html .= "<p>" . $episode['resume'] . " minutes</p>";
            $html .= "<p>" . $episode['duree'] . "</p>";
        }
        return $html;
        // TODO: Implement execute() method.
    }
}