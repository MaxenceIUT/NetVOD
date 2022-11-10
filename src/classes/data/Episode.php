<?php

namespace iutnc\netvod\data;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\users\User;

class Episode
{

    protected int $id, $numero, $duree, $serie_id;
    protected string $titre, $resume, $file;

    /**
     * Method to find all episodes of a serie
     * @param int $serie_id Serie id
     * @return array Array of Episode
     */
    public static function getAllEpisodesFromSerie(int $serie_id): array
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select * from episode where serie_id = :serie_id";
        $statement = $pdo->prepare($query);
        $statement->execute(['serie_id' => $serie_id]);

        $episodes = [];
        while ($episode = $statement->fetch()) {
            $episodes[] = Episode::find($episode['id']);
        }
        return $episodes;
    }

    /**
     * Method to find an episode by id (like a constructor)
     * @param int $id Episode id
     * @return Episode Episode object
     */
    public static function find(int $id): Episode
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select * from episode where id = :id";
        $statement = $pdo->prepare($query);
        $statement->execute(['id' => $id]);

        return $statement->fetchObject(Episode::class);
    }

//    public function toHTML(): string
//    {
//        return <<<END
//        <h3>$this->titre</h3>
//        <p>$this->resume</p>
//        <p>Durée: $this->duree</p>
//        <video width="854" height="480" controls autoplay>
//            <source src="assets/video/$this->file" type="video/mp4">
//        </video>
//        END;
//    }

    /**
     * @param User $user the user to check
     * @return bool true if the user already comment this episode, false otherwise
     */
    public function isBookmarkedBy(User $user): bool
    {
        $comments = $user->getComment($this->serie_id);
        return ($comments != null) ? true : false;
    }

    public function __get($name)
    {
        return $this->$name;
    }

}
