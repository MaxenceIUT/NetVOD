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
        return $serie->toHTML();
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