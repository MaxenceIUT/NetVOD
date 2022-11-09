<?php

namespace iutnc\netvod\data;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\users\User;

class Series
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
            $series[] = Series::find($serie['id']);
        }
        return $series;
    }

    public static function find(int $id): Series
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "SELECT * FROM series WHERE id = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(":id", $id);
        $statement->execute();
        return $statement->fetchObject(Series::class);
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function isBookmarkedBy(User $user): bool
    {
        $pdo = ConnectionFactory::getConnection();
        $query = "select * from bookmarked_series where email = ? and id = ?";
        $statement = $pdo->prepare($query);

        $email = $user->email;

        $statement->bindParam(1, $email);
        $statement->bindParam(2, $this->id);
        $statement->execute();
        $result = $statement->rowCount();

        return $result > 0;
    }

}