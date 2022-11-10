<?php

namespace iutnc\netvod\action\api;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Episode;
use iutnc\netvod\renderer\Renderer;
use iutnc\netvod\renderer\ReviewRenderer;

class AlreadyComment extends Action
{

    public function execute(): string
    {
        $episode = Episode::find($_GET['id']);
        $user = Auth::GetCurrentUser();
        $review = $user->getReview($episode->serie_id);

        $renderer = new ReviewRenderer($review);
        $reviewRender = $renderer->render(Renderer::FULL);

        return <<<END
        <div class="user-review">
            <h3>Votre avis est publié ! <a href="index.php?action=show-reviews&id={$episode->serie_id}">Voir tous les avis</a></h3>
            $reviewRender
        </div>           
        END;
    }

    public function getActionName(): string
    {
        return "already-comment";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}