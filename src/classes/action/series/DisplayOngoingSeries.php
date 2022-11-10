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

        $ongoingSeries = array_reduce($user->getOngoingSeries(), function ($carry, $series) use ($user) {
            if (!$series->isAlreadySeenBy($user)) {
                $carry[] = $series;
            }
            return $carry;
        }, []);

        foreach ($ongoingSeries as $series) {
            if (!$series->isAlreadySeenBy($user)) {
                $renderer = new OngoingSeriesRenderer($series);
                $html .= $renderer->render(Renderer::COMPACT);
            }
        }

        if (count($ongoingSeries) == 0) {
            $html .= <<<END
            <p>Vous n'avez pas de série en cours de visionnage</p>
            END;
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