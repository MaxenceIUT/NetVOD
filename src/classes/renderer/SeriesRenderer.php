<?php

namespace iutnc\netvod\renderer;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Episode;
use iutnc\netvod\data\Series;

class SeriesRenderer implements Renderer
{

    private Series $series;

    /**
     * @param Series $series
     */
    public function __construct(Series $series)
    {
        $this->series = $series;
    }


    function render(int $mode): string
    {
        if ($mode == self::FULL) {
            $html = <<<END
            <h3>{$this->series->titre}</h3>
            <img src="assets/img/{$this->series->image}" alt="Image de la série {$this->series->titre}">
            <p>{$this->series->descriptif}</p>
            END;

            $user = Auth::getCurrentUser();
            if ($user != null) {
                if ($this->series->isBookmarkedBy($user)) {
                    $html .= "<a href='index.php?action=show-series-details&id={$this->series->id}&bookmark=false'>Retirer des favoris</a>";
                } else {
                    $html .= "<a href='index.php?action=show-series-details&id={$this->series->id}&bookmark=true'>Ajouter aux favoris</a>";
                }
            }

            $score = $this->series->getScore();
            $score = $score == -1 ? "N/A" : $score;

            $html .= <<<END
            <p>Année: {$this->series->annee}</p>
            <p>Date d'ajout: {$this->series->date_ajout}</p>
            <p>Note: $score</p>
            END;

            $episodes = Episode::getAllEpisodesFromSerie($this->series->id);
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
        } else {
            return <<<END
            <ul>
                <div class="serie">
                    <li><a href="index.php?action=show-series-details&id={$this->series->id}">{$this->series->titre}</a></li>
                    <img src="{$this->series->image}" alt="Image de la série {$this->series->titre}">
                </div>
            </ul>
            END;
        }
    }

}