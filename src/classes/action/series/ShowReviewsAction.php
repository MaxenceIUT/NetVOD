<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\data\Series;
use iutnc\netvod\renderer\Renderer;
use iutnc\netvod\renderer\ReviewRenderer;

class ShowReviewsAction extends Action
{

    public function execute(): string
    {
        $id = $_GET['id'];
        $series = Series::find($id);

        $html = "<h1>Reviews de la série: " . $series->titre . "</h1>";

        $reviews = $series->getReviews();

        foreach ($reviews as $review) {
            $renderer = new ReviewRenderer($review);
            $html .= $renderer->render(Renderer::FULL);
        }

        return $html;
    }

    public function getActionName(): string
    {
        return "show-reviews";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}