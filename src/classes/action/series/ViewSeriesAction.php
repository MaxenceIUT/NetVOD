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
        $html = "";
        if ($this->http_method == "GET") {
            $sort = $_GET['sort'] ?? "titre";
            $i = $_GET['i'] ?? null;
            $sorts = ["title", "date", "episodes", "note"];
            $text = ["Titre", "Date d'ajout", "Nombre d'épisodes", "Note"];
            $genres = $this->getAvailableGenres();
            $public = $this->getAvailablePublics();
            $html = <<<END
            <h1>Catalogue des séries<br></h1>
            END;
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

            <label for='select-sort'>Trier par</label>
            END;

            $html .= "<select name='sort'>";
            for ($increment = 0; $increment < count($sorts); $increment++) {
                if ($sorts[$increment] == $sort) {
                    $html .= "<option value='$sorts[$increment]' selected>$text[$increment]</option>";
                } else {
                    $html .= "<option value='$sorts[$increment]'>$text[$increment]</option>";
                }
            }
            $html .= "</select>";

            if ($i == 'true') {
                $html .= <<<END
                        <label for="i">Inverser l'ordre</label>
                        <input type="checkbox" name="i" id="i" checked>
                        <input type="submit" value="Trier">
                    END;
            } else {
                $html .= <<<END
                        <label for="i">Inverser l'ordre</label>
                        <input type = "checkbox" name = "i" id = "i" >
                        <input type = "submit" value = "Trier" >
                    </form>
                    END;
            }
            $param = [];
            if (isset($_GET['genre'])) {
                $genre = $_GET['genre'];
                $param = ["genre" => $genre];
            }
            if (isset($_GET['public'])) {
                $public = $_GET['public'];
                $param = ["public" => $public];
            }

            $serieList = Series::getAllGenre($param);
            $tri = "title";
            $i = "false";
            if (isset($_GET['sort'])) {
                $tri = $_GET['sort'];
            }
            if (isset($_GET['i'])) {
                $i = $_GET['i'];
            }

            $seriesList = Series::sortBy($tri, $i, $serieList);
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
            if (isset($_POST['sort'])) {
                $sort = $_POST['sort'];
                $header .= "&sort=$sort";
                $i = "false";
                if (isset($_POST['i'])) {
                    if ($_POST['i'] == "on") {
                        $i = "true";
                    }
                }
                $header .= "&i=$i";
            }
            header($header);

            $html .= "</div>";
        }
        return $html;

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

    public function getActionName(): string
    {
        return "view-series";
    }

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }

}