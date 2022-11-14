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
                <div class="review-form">
                    <h4>Donne nous ton avis sur cette série</h4>
                    <form action="?action=show-episode-details&id={$_GET['id']}&bookmark=false" method="post">
                        <label for="notation">Note</label>
                        <input type="number" name="notation" placeholder="Note (0-10)" min="0" max="10" required>
                        <label for="comment">Commentaire</label>
                        <textarea name="comment" id="comment" required></textarea>
                        <input type="submit" value="Envoyer">
                    </form>
                </div>

            END;
        } else {
            $user = Auth::getCurrentUser();
            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
            $notation = filter_var($_POST['notation'], FILTER_SANITIZE_NUMBER_INT);
            if ($notation < 0 || $notation > 10) {
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