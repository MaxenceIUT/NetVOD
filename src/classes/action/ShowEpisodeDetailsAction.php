<?php

namespace iutnc\netvod\action;

use iutnc\netvod\lists\Episode;

class ShowEpisodeDetailsAction extends Action
{

    public function execute(): string
    {
        $episode = Episode::find($_GET['id']);

        return <<<END
        <h3>$episode->titre</h3>
        <p>$episode->resume</p>
        <p>Durée: $episode->duree</p>
        <video width="854" height="480" controls autoplay>
            <source src="assets/video/$episode->file" type="video/mp4">
        </video>
        END;
    }

    public function getActionName(): string
    {
        return "show-episode-details";
    }
}