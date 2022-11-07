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
        $html .= "<h1>" . $serie->titre . "</h1>";
        $html .= "<img src='assets/img/" . $serie->image . "' alt=''>";
        $html .= "<p>Résumé:" . $serie->descriptif . "</p>";
        $html .= "<p>Année: " . $serie->annee . "</p>";
        $html .= "<p>Date d'ajout: " . $serie->dateAjout . "</p>";
        $idS = $serie->id;
        $queryEpisodes = "SELECT * FROM episode WHERE serie_id = :id";
        $statement = $pdo->prepare($queryEpisodes);
        $statement->bindParam(":id", $idS);
        $statement->execute();
        $episodes = $statement->fetchAll();
        $nbepisodes = count($episodes);
        $html .= "<p>" . $nbepisodes . " épisodes</p>";
        $html .= "<ul>";
        foreach ($episodes as $episode) {

            $nom = "Épisode " . $episode['numero'] . ": " . $episode['titre'];
            $html .= "<li><a href='index.php?action=show-episode-details&id=" . $episode['id'] . "'>$nom</a>";
            $html .= $episode['duree'] . " minutes</li>";
        }
        $html .= "</ul>";
        return $html;
    }

    public function getActionName(): string
    {
        return "show-series-details";
    }
}