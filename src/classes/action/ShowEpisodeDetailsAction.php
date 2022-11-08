<?php

namespace iutnc\netvod\action;

use iutnc\netvod\lists\Episode;

class ShowEpisodeDetailsAction extends Action
{

    public function execute(): string
    {
        $episode = Episode::find($_GET['id']);
        $user = $_SESSION['user'];
        $user->addOnGoingSeries($episode->serie_id);
        return $episode->toHTML();
    }

    public function getActionName(): string
    {
        return "show-episode-details";
    }
}