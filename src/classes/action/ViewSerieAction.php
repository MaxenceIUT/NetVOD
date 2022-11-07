<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\lists\Serie;

class ViewSerieAction extends Action
{

    public function execute(): string
    {
        $html = "Series disponible sur le catalogue: <br>";

        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->query("SELECT id FROM serie");
        while ($serie = $statement->fetch()) {
            $serie = new Serie($serie['id']);
            $html .= "<div class='serie'>";
            $html .= "<h2>" . $serie->titre . "</h2>";
            $html .= "</div>";
        }

        return $html;
    }

}