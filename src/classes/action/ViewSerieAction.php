<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\lists\Serie;

class ViewSerieAction extends Action
{

    public function execute(): string
    {
        $html = "Series disponible(s) sur le catalogue: <br>";

        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->query("SELECT id FROM serie");

        $html .= "<ul>";
        while ($serie = $statement->fetch()) {
            $serie = new Serie($serie['id']);
            $html .= "<div class='serie'>";
            $href = "index.php?action=show-series-details&id=" . $serie->id;
            $html .= "<li><a href=$href> $serie->titre</a></li>";
            $html .= "<img src='$serie->image' alt='image de la série $serie->titre'>";
            $html .= "</div>";
        }

        return $html;
    }

}