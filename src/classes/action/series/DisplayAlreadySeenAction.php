<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Series;
use iutnc\netvod\renderer\Renderer;
use iutnc\netvod\renderer\SeriesRenderer;

class DisplayAlreadySeenAction extends Action
{

    public function execute(): string
    {
        $user = Auth::getCurrentUser();

        $html = <<<END
        <div class="already-seen">
            <h3>Série(s) déjà regardée(s)</h3>
            <div class="items">
        END;

        $alreadySeenSeries = array_reduce(Series::getAll(), function ($carry, $item) use ($user) {
            if ($item->isAlreadySeenBy($user)) {
                $carry[] = $item;
            }
            return $carry;
        }, []);

        foreach ($alreadySeenSeries as $series) {
            $renderer = new SeriesRenderer($series);
            $html .= $renderer->render(Renderer::COMPACT);
        }

        if (count($alreadySeenSeries) == 0) {
            $html .= <<<END
            <p>Vous n'avez pas terminé de regarder une série</p>
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
        return "display-already-seen";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}