<?php

namespace iutnc\netvod\action\api;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Episode;
use iutnc\netvod\data\Review;

class AddComment extends Action
{

    public function execute(): string
    {
        $episode = Episode::find($_GET['id']);
        $html = "";
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $html .= <<<END
                <div class="commentsForm">
                    <h3>comments</h3>
                    <form action="?action=show-episode-details&id={$_GET['id']}&bookmark=false" method="post">
                        <input type="Number" name="notation" placeholder="Note (0-10)">
                        <textarea name="comment" id="comment" cols="10" rows="1"></textarea>
                        <input type="submit" value="Envoyer">
                    </form>
                </div>

            END;
        } else {
            $user = Auth::getCurrentUser();
            $comment = filter_var($_POST['comment'], FILTER_UNSAFE_RAW);
            $notation = filter_var($_POST['notation'], FILTER_VALIDATE_INT);
            if ($notation < 0 && $notation > 10) {
                return "Mauvaise notation";
            }
            $review = Review::create($user->email, $episode->serie_id, $notation, $comment);
            $review->addBase();
            header("Location: ?action=show-episode-details&id={$_GET['id']}&bookmark=true");
        }

        return $html;
    }

    public function getActionName(): string
    {
        return "add-comment";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}