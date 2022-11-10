<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\renderer\Renderer;
use iutnc\netvod\renderer\SeriesRenderer;

class DisplayBookmarkedSeries extends Action
{

    public function execute(): string
    {
        $user = Auth::getCurrentUser();

        $html = <<<END
        <div class="bookmarked">
            <h3>Revivre les meilleurs moments</h3>
            <div class="items">
        END;

        $bookmarkedSeries = $user->getBookmarkedSeries();
        foreach ($bookmarkedSeries as $series) {
            $renderer = new SeriesRenderer($series);
            $html .= $renderer->render(Renderer::COMPACT);
        }

        if (count($bookmarkedSeries) == 0) {
            $html .= <<<END
            <p>Vous n'avez pas encore de série préférée</p>
            END;
        }

        $html .= <<<END
            </div>
        </div>
        END;
        return $html;
    }

    public function getActionName(): string
    {
        // TODO: Implement getActionName() method.
    }

    public function shouldBeAuthenticated(): bool
    {
        // TODO: Implement shouldBeAuthenticated() method.
    }
}