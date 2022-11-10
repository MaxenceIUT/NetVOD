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
            <main>
                <div class="already-seen">
                    <h3>Série(s) déjà regardée(s)</h3>
                    <div class="items">
            END;

        $seriesList = Series::getAll();
        foreach ($seriesList as $series) {
            if ($series->isAlreadySeenBy($user)) {
                $renderer = new SeriesRenderer($series);
                $html .= $renderer->render(Renderer::COMPACT);
            }
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