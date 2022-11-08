<?php

namespace iutnc\netvod\action;

use iutnc\netvod\lists\Episode;
use iutnc\netvod\lists\Serie;

class ShowSerieDetailsAction extends Action
{

    public function execute(): string
    {
        $id = $_GET['id'];
        $serie = Serie::find($id);

        $html = <<<END
        <h3>$serie->titre</h3>
        <img src="assets/img/$serie->image" alt="Image de la série $serie->titre">
        <p>$serie->descriptif</p>
        <p>Année: $serie->annee</p>
        <p>Date d'ajout: $serie->date_ajout</p>
        END;

        $episodes = Episode::getAllEpisodesFromSerie($id);
        $episodeCount = count($episodes);

        $html .= <<<END
        <p>$episodeCount épisodes</p>
        <ul>
        END;

        foreach ($episodes as $episode) {
            $nom = "Épisode " . $episode->numero . ": " . $episode->titre;
            $html .= <<<END
            <li><a href="index.php?action=show-episode-details&id=$episode->id">$nom</a>$episode->duree minutes</li>
            END;
        }
        $html .= "</ul>";

        return $html;
    }

    public function getActionName(): string
    {
        return "show-serie-details";
    }
}