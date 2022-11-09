<?php

namespace iutnc\netvod\lists;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\users\User;

class Serie
{
    protected int $id;
    protected string $titre, $descriptif, $date_ajout, $annee;
    protected string $image = "";

    public static function getAll(): array
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->query("SELECT id FROM series");
        $series = [];
        while ($serie = $statement->fetch()) {
            $series[] = Serie::find($serie['id']);
        }
        return $series;
    }

    public static function find(int $id): Serie
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "SELECT * FROM series WHERE id = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(":id", $id);
        $statement->execute();
        return $statement->fetchObject(Serie::class);
    }

    public function toHTML(): string
    {
        $html = <<<END
        <h3>$this->titre</h3>
        <img src="assets/img/$this->image" alt="Image de la série $this->titre">
        <p>$this->descriptif</p>
        END;
        if (!User::hasFavorite($this->id)) {
            $html .= <<<END
        <div class="add-favorite">
                <a href='index.php?action=show-serie-details&id=$this->id&fav=true'>Ajouter aux favoris</a>
        </div>
        END;
        } else if (User::hasFavorite($this->id)) {
            $html .= <<<END
        <div class="remove-favorite">
            <a href='index.php?action=show-serie-details&id=$this->id&fav=false '>Retirer des favoris</a>
        </div>
        END;
        }
        $html .= <<<END
        <p>Année: $this->annee</p>
        <p>Date d'ajout: $this->date_ajout</p>
        END;

        $episodes = Episode::getAllEpisodesFromSerie($this->id);
        $episodeCount = count($episodes);

        $html .= <<<END
        <p>$episodeCount épisodes</p>
        <ul>
        END;

        foreach ($episodes as $episode) {
            $nom = "Épisode " . $episode->numero . ": " . $episode->titre;
            $html .= <<<END
            <li><a href="index.php?action=show-episode-details&id=$episode->id">$nom</a>$episode->duree minutes</li>
            END;
        }
        $html .= "</ul>";
        return $html;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}