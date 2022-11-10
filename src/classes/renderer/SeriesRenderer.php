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
            <div class="series series__full">
                <img class="background" src="assets/img/series/{$this->series->img}" alt="Image de la série {$this->series->titre}">
                <h1>{$this->series->titre} <span>{$this->series->annee}</span></h1>
                <p>{$this->series->descriptif}</p>
            END;

            $user = Auth::getCurrentUser();
            if ($user != null) {
                if ($this->series->isBookmarkedBy($user)) {
                    $html .= <<<END
                    <a class="button-link button-link__red button-link__plain" href='index.php?action=show-series-details&id={$this->series->id}&bookmark=false'>Retirer des favoris</a>
                    END;
                } else {
                    $html .= <<<END
                    <a class="button-link button-link__plain" href='index.php?action=show-series-details&id={$this->series->id}&bookmark=true'>Ajouter aux favoris</a>
                    END;
                }
            }

            $score = $this->series->getScore();
            $score = $score == -1 ? "N/A" : $score;

            $reviewCount = count($this->series->getReviews());

            $html .= <<<END
                <div class="reviews">
                    <h5>$score<span>/10</span></h5>
                    <a class="button-link button-link__text" href="index.php?action=show-reviews&id={$this->series->id}">Voir les {$reviewCount} avis</a>            
                </div>
            END;

            $episodes = Episode::getAllEpisodesFromSerie($this->series->id);
            $episodeCount = count($episodes);

            $html .= <<<END
                <div class="episodes">
                    <h5>$episodeCount épisodes</h5>
                    <ul>
            END;

            foreach ($episodes as $episode) {
                $renderEpisode = new EpisodeRenderer($episode);
                $html .= $renderEpisode->render(Renderer::COMPACT);
            }

            $html .= <<<END
                    </ul>
                </div>
                <p class="available">Disponible sur NetVOD depuis le {$this->series->date_ajout}</p>
            </div>
            END;
            return $html;
        } else {
            return <<<END
            <a class="card" href="index.php?action=show-series-details&id={$this->series->id}">
                <h4>{$this->series->titre}</h4>
                <p>{$this->series->descriptif}</p>
                <img src="assets/img/series/{$this->series->img}" alt="Image de la série {$this->series->titre}">
            </a>
            END;
        }
    }

}