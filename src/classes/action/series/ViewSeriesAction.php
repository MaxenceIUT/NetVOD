<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\data\Series;
use iutnc\netvod\renderer\Renderer;
use iutnc\netvod\renderer\SeriesRenderer;

class ViewSeriesAction extends Action
{

    public function execute(): string
    {
        $html = "<h3>Series disponible(s) sur le catalogue: <br></h3>";

        $seriesList = Series::getAll();
        foreach ($seriesList as $series) {
            $renderer = new SeriesRenderer($series);
            $html .= $renderer->render(Renderer::COMPACT);
        }

        return $html;
    }

    public function getActionName(): string
    {
        return "view-series";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }

}