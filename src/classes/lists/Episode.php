<?php

namespace iutnc\netvod\lists;

use iutnc\netvod\db\ConnectionFactory;

class Episode
{
    protected string $titre, $resume, $file;
    protected int $duree, $id;

    public function __construct(int $id)
    {
        $this->id = $id;
        $pdo = ConnectionFactory::getConnection();
        $query = "select * from episode where id = :id";
        $statement = $pdo->prepare($query);
        $statement->execute(['id' => $id]);
        $episode = $statement->fetch();
        $this->titre = $episode['titre'];
        $this->resume = $episode['resume'];
        $this->duree = $episode['duree'];
        $this->file = $episode['file'];
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
