<?php

namespace iutnc\netvod\action;

use iutnc\netvod\lists\Serie;

class ShowSerieDetailsAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $id = $_GET['id'];
        $serie = Serie::find($id);
        if ($_GET['fav'] == 'true') {
            $a = new AddFavoriteSerieAction();
            $html .= $a->execute();
        } else {
            $a = new RemoveFavoriteSerieAction();
            $html .= $a->execute();
        }
        return $serie->toHTML() . $html;
    }

    public function getActionName(): string
    {
        return "show-serie-details";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }
}