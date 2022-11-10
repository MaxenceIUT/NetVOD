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
        $html = <<<END
            <h1>Catalogue des séries<br></h1>
            <div class="items">
        END;
        if ($this->http_method == "GET") {
            $html .= <<<END
                <form action="index.php?action=view-series" method="post">
                <label for='select-genre'>Genre</label>
                <select name="genre">
                    <option value="all">Tous les genres</option>
                    <option value="Action">Action</option>
                    <option value="Film à énigme">Film à énigme</option>
                    <option value="Drama historique">Drama historique</option>
                    <option value="Film d'horreur">Film d'horreur</option>
                </select> 
                <label for='select-public'>Public</label>
                <select name="public">
                    <option value="all">Tous public</option>
                    <option value="Enfants">Enfants</option>
                    <option value="Adolescents">Adolescents</option>
                    <option value="Jeunes adultes">Jeunes adultes</option>
                    <option value="Adultes">Adultes</option>
                </select> 
                <input type="submit" value="Rechercher">  
            </form>
            END;

            $seriesList = Series::getAll();
            if (isset($_POST['genre']) && $_POST['public']) {
                $html .= "tasoeur";
            } else {
                $seeGenre = "";
                $param = [];
                if (isset($_GET['genre'])) {
                    $genre = $_GET['genre'];
                    $seeGenre = " avec comme genre $genre";
                    $param = ["genre" => $genre];
                }
                if (isset($_GET['public'])) {
                    $public = $_GET['public'];
                    $seeGenre = " avec comme public $public";
                    $param = ["public" => $public];
                }
                $seriesList = Series::getAllGenre($param);
            }
            $html .= "<h3>Series disponible(s) sur le catalogue$seeGenre: <br></h3>";
            foreach ($seriesList as $series) {
                $renderer = new SeriesRenderer($series);
                $html .= $renderer->render(Renderer::COMPACT);
            }
        } else {
            $header = "Location: index.php?action=view-series";
            if (isset($_POST['genre'])) {
                $Genre = $_POST["genre"];
                if ($Genre != "all") {
                    $header .= "&genre=$Genre";
                }
            }
            if (isset($_POST['public'])) {
                $public = $_POST['public'];
                if ($public != "all") {
                    $header .= "&public=$public";
                }
            }
            header($header);
        }

        $html .= "</div>";
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