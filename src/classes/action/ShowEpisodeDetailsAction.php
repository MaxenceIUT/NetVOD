<?php

namespace iutnc\netvod\action;

use iutnc\netvod\lists\Episode;
use iutnc\netvod\lists\Review;

class ShowEpisodeDetailsAction extends Action
{

    public function execute(): string
    {
        $episode = Episode::find($_GET['id']);
        $user = $_SESSION['user'];
        $user->addOnGoingSeries($episode->serie_id);
        $html = $episode->toHTML();
        $comments = $user->getComment($episode->serie_id);
        //Si le commentaire existe on l'affiche, sinon on affiche un formulaire
        if ($comments != null) {
            $html .= <<<END
            <div class="comments">
                <h3>comment laissé</h3>
                $comments->comment
                <h3>Note donné pour la serie:</h3>
                $comments->score
            </div>           
            END;
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $html .= <<<END
                <div class="commentsForm">
                    <h3>comments</h3>
                    <form action="?action=show-episode-details&id={$_GET['id']}" method="post">
                        <input type="Number" name="notation" placeholder="Note (0-10)">
                        <textarea name="comment" id="comment" cols="10" rows="1"></textarea>
                        <input type="submit" value="Envoyer">
                    </form>
                </div>

            END;
            } else {
                $comment = filter_var($_POST['comment'], FILTER_UNSAFE_RAW);
                $notation = filter_var($_POST['notation'], FILTER_VALIDATE_INT);
                if ($notation < 0 && $notation > 10) {
                    return "";
                }
                $review = Review::create($user->email, $episode->serie_id, $notation, $comment);
                $review->addBase();
            }
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