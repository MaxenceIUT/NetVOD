<?php

namespace iutnc\netvod\action;

use iutnc\netvod\data\Series;
use iutnc\netvod\renderer\Renderer;
use iutnc\netvod\renderer\SeriesRenderer;

class ShowSerieDetailsAction extends Action
{

    public function execute(): string
    {
        $id = $_GET['id'];
        $series = Series::find($id);

        $html = "";

        if (isset($_GET['bookmark'])) {
            if ($_GET['bookmark'] == 'true') {
                $action = new AddFavoriteSerieAction();
            } else {
                $action = new RemoveFavoriteSerieAction();
            }
            $html .= $action->execute();
        }

        $renderer = new SeriesRenderer($series);
        $html .= $renderer->render(Renderer::FULL);

        return $html;
    }

    public function getActionName(): string
    {
        return "show-series-details";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}