<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\action\api\AddComment;
use iutnc\netvod\action\api\AlreadyComment;

use iutnc\netvod\data\Episode;
use iutnc\netvod\data\Review;
use iutnc\netvod\renderer\EpisodeRenderer;
use iutnc\netvod\renderer\Renderer;

class ShowEpisodeDetailsAction extends Action
{

    public function execute(): string
    {
        $episode = Episode::find($_GET['id']);
        $user = $_SESSION['user'];
        $user->addWatchedEpisode($episode);
        $renderEpisode = new EpisodeRenderer($episode);
        $html = $renderEpisode->render(Renderer::FULL);
        $comments = $user->getComment($episode->serie_id);
        //Si le commentaire existe on l'affiche, sinon on affiche un formulaire
        if (isset($_GET['bookmark'])) {
            if ($_GET['bookmark'] == 'true') {
                $action = new AlreadyComment();
            } else {
                $action = new AddComment();
            }
            $html .= $action->execute();
        }
        return $html;
    }


    function getActionName(): string
    {
        return "show-episode-details";
    }

    public function shouldBeAuthenticated(): bool
    {
        return false;
    }
}