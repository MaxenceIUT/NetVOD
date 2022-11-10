<?php

namespace iutnc\netvod\renderer;

use iutnc\netvod\auth\Auth;
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
        //Resum of the episode
        if ($mode == self::COMPACT) {
            $nom = "Épisode " . $this->episode->numero . ": " . $this->episode->titre;
            $user = Auth::getCurrentUser();
            if ($user != null) {
                $nomEp = "$nom</a>{$this->episode->duree} minutes";
                if (!$this->episode->isBookmarkedBy($user)) {
                    $html .= "<li><a href='index.php?action=show-episode-details&id={$this->episode->id}&bookmark=false'>$nomEp</a></li>";
                } else {
                    $html .= "<li><a href='index.php?action=show-episode-details&id={$this->episode->id}&bookmark=true'>$nomEp</a></li>";
                }
            }

            //Full details of the episode with the video
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