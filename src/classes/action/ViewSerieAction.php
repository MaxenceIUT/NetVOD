<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\lists\Serie;

class ViewSerieAction extends Action
{

    public function execute(): string
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->query("SELECT id FROM serie");

        $html = "<h3>Series disponible(s) sur le catalogue: <br></h3>";

        while ($serie = $statement->fetch()) {
            $serie = Serie::find($serie['id']);

            $html .= <<<END
            <ul>
                <div class="serie">
                    <li><a href="index.php?action=show-serie-details&id=$serie->id">$serie->titre</a></li>
                    <img src="$serie->image" alt="Image de la série $serie->titre">
                </div>
            </ul>
            END;
        }

        return $html;
    }

    public function getActionName(): string
    {
        return "view-serie";
    }

}