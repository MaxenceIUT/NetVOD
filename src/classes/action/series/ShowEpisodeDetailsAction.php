<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\action\api\AddComment;
use iutnc\netvod\action\api\AlreadyComment;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Episode;
use iutnc\netvod\renderer\EpisodeRenderer;
use iutnc\netvod\renderer\Renderer;

class ShowEpisodeDetailsAction extends Action
{

    public function execute(): string
    {
        $episode = Episode::find($_GET['id']);
        $user = Auth::getCurrentUser();
        $user->addWatchedEpisode($episode);
        $renderEpisode = new EpisodeRenderer($episode);
        $html = $renderEpisode->render(Renderer::FULL);

        $comment = $user->getReview($episode->serie_id);

        if ($comment != null) {
            $action = new AlreadyComment();
        } else {
            $action = new AddComment();
        }

        $html .= $action->execute();

        return $html;
    }


    function getActionName(): string
    {
        return "show-episode-details";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }

}