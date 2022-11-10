<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\renderer\OngoingSeriesRenderer;
use iutnc\netvod\renderer\Renderer;

class DisplayOngoingSeries extends Action
{

    public function execute(): string
    {
        $user = Auth::getCurrentUser();
        $html = <<<END
        <div class="continue-watching">
            <h3>Continuer à regarder</h3>
            <div class="items">
        END;

        $ongoingSeries = $user->getOngoingSeries();

        foreach ($ongoingSeries as $series) {
            if (!$series->isAlreadySeenBy($user)) {
                $renderer = new OngoingSeriesRenderer($series);
                $html .= $renderer->render(Renderer::COMPACT);
            }
        }

        $html .= <<<END
            </div>
        </div>
        END;

        return $html;
    }

    public
    function getActionName(): string
    {
        return "ongoing-series";
    }

    public
    function shouldBeAuthenticated(): bool
    {
        return true;
    }
}