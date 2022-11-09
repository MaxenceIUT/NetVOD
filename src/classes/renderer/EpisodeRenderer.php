<?php

namespace iutnc\netvod\renderer;

use iutnc\netvod\data\Episode;

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
            $nom = "Épisode " . $this->episode->numero . ": " . $this->episode->titre;
            $html .= <<<END
                <li><a href="index.php?action=show-episode-details&id={$this->episode->id}">$nom</a>{$this->episode->duree} minutes</li>
                END;
        } else {
            $html .= <<<END
            <h3>{$this->episode->titre}</h3>
            <p>{$this->episode->resume}</p>
            <p>Durée: {$this->episode->duree}</p>
            <video width="854" height="480" controls autoplay>
                <source src="assets/video/{$this->episode->file}" type="video/mp4">
            </video>
            END;
        }
        return $html;
    }

}