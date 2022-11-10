<?php

namespace iutnc\netvod\action\api;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Episode;

class AlreadyComment extends Action
{

    public function execute(): string
    {
        $episode = Episode::find($_GET['id']);
        $user = Auth::GetCurrentUser();
        $comments = $user->getComment($episode->serie_id);
        $html = <<<END
            <div class="comments">
                <h3>comment laissé</h3>
                $comments->comment
                <h3>Note donné pour la serie:</h3>
                $comments->score
            </div>           
            END;
        return $html;

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