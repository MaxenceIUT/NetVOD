<?php

namespace iutnc\netvod\lists;

use iutnc\netvod\db\ConnectionFactory;

class Episode
{

    protected int $id, $numero, $duree, $serie_id;
    protected string $titre, $resume, $file;

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

    public static function find(int $id): Episode
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select * from episode where id = :id";
        $statement = $pdo->prepare($query);
        $statement->execute(['id' => $id]);

        return $statement->fetchObject(Episode::class);
    }

    public function __get($name)
    {
        return $this->$name;
    }

}
