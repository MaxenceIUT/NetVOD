<?php

namespace iutnc\netvod\action;

use iutnc\netvod\lists\Episode;

class ShowEpisodeDetailsAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $episode = new Episode($_GET['id']);
        $html .= "<h1>" . $episode->titre . "</h1>";
        $html .= "<p>Résumé:" . $episode->resume . "</p>";
        $html .= "<p>Durée: " . $episode->duree . "</p>";
        $html .= "<video width = '854' height= '480' controls>";
        $html .= "<source src='assets/video/" . $episode->file . "' type='video/mp4'>";
        $html .= "</video>";
        return $html;
    }
}