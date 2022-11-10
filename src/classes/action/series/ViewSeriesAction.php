<?php

namespace iutnc\netvod\action\series;

use iutnc\netvod\action\Action;
use iutnc\netvod\data\Series;
use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\renderer\Renderer;
use iutnc\netvod\renderer\SeriesRenderer;
use PDO;

class ViewSeriesAction extends Action
{

    public function execute(): string
    {
        $html = <<<END
            <h1>Catalogue des séries<br></h1>
            <div class="items">
        END;
        if ($this->http_method == "GET") {

            $genres = $this->getAvailableGenres();
            $public = $this->getAvailablePublics();

            $html .= <<<END
                <form action="index.php?action=view-series" method="post">
                <label for='select-genre'>Genre</label>
                <select name="genre">
                    <option value="all">Tous les genres</option>
                END;

            foreach ($genres as $genre) {
                $nomGenre = $genre['genre'];
                $html .= "<option value='$nomGenre'>$nomGenre</option>";
            }

            $html .= <<<END
                </select> 
                <label for='select-public'>Public</label>
                <select name="public">
                    <option value="all">Tous public</option>
                END;

            foreach ($public as $pub) {
                $nomPublic = $pub['type_public'];
                $html .= "<option value='$nomPublic'>$nomPublic</option>";
            }

            $html .= <<<END
                </select> 
                <input type="submit" value="Rechercher">  
            </form>
            END;

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

    public function getAvailableGenres(): array
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select id, genre from genres";
        $statement = $pdo->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    private function getAvailablePublics()
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select id, type_public from types_publics";
        $statement = $pdo->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}