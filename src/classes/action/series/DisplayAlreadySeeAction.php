<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Series;
use iutnc\netvod\renderer\Renderer;
use iutnc\netvod\renderer\SeriesRenderer;

class DisplayAlreadySeeAction extends Action
{

    public function execute(): string
    {
        $user = Auth::getCurrentUser();

        $html = <<<END
            <main>
                <div class="AlreadySee">
                    <h3>Serie déjà regardé</h3>
                    <div class="items">
            END;

        $SerieList = Series::getAll();
        foreach ($SerieList as $series) {
            if ($series->isAlreadySee($user)) {
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
        return "display-already-see";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}