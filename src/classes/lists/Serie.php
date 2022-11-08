<?php

namespace iutnc\netvod\lists;

use iutnc\netvod\db\ConnectionFactory;

class Serie
{
    protected int $id;
    protected string $titre, $descriptif, $date_ajout, $annee;
    protected string $image = "";

    public static function getAll(): array
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->query("SELECT id FROM serie");
        $series = [];
        while ($serie = $statement->fetch()) {
            $series[] = Serie::find($serie['id']);
        }
        return $series;
    }

    public static function find(int $id): Serie
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "SELECT * FROM serie WHERE id = :id";
        $statement = $pdo->prepare($query);
        $statement->execute(['id' => $id]);
        return $statement->fetchObject(Serie::class);
    }

    public function __get($name)
    {
        return $this->$name;
    }

}