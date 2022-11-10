<?php

namespace iutnc\netvod\renderer;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Episode;
use iutnc\netvod\data\Series;

class EpisodeRenderer implements Renderer
{
    private Episode $episode;

    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    public function render(int $mode): string
    {
        $html = "";
        if ($mode == self::COMPACT) {
            $user = Auth::getCurrentUser();

            $nom = "Ep. {$this->episode->numero} • {$this->episode->titre}";

            if ($user != null) {
                $html .= <<<END
                <li>
                    <a class="card" href='index.php?action=show-episode-details&id={$this->episode->id}'>
                        <h4>$nom ({$this->episode->duree} min)</h4>
                        <p>{$this->episode->resume}</p>
                    </a>
                </li>
                END;
            }
        } else {
            $series = Series::find($this->episode->serie_id);
            $renderer = new SeriesRenderer($series);
            $seriesRender = $renderer->render(self::COMPACT);

            $html .= <<<END
            <div class="episode-player">
                <div class="infos">
                    <img class="background" src="assets/img/series/{$series->img}" alt="{$series->titre}">
                    <div class="series-infos">
                        <h1>Vous regardez la série {$series->titre}</h1>
                        $seriesRender
                    </div>
                    <div class="episode-infos">
                        <h3>Ep. {$this->episode->numero} • {$this->episode->titre}</h3>
                        <p>{$this->episode->resume}</p>
                        <span>{$this->episode->duree} minutes</span>
                    </div>
                </div>
                <video width="854" height="480" controls autoplay>
                    <source src="assets/video/{$this->episode->file}" type="video/mp4">
                </video>
            </div>
            END;
        }
        return $html;
    }

}