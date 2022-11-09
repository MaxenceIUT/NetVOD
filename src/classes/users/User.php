<?php

namespace iutnc\netvod\users;

use InvalidArgumentException;
use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\lists\Review;
use iutnc\netvod\lists\Serie;

class User
{

    protected string $email;
    protected string $password;
    protected string $first_name;
    protected string $last_name;
    protected ?string $favorite_genre;

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new InvalidArgumentException("Property $name does not exist");
        }
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new InvalidArgumentException("Property $name does not exist");
        }
    }

    public function save(): bool
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name WHERE email = :email");
        $statement->bindParam(":first_name", $this->first_name);
        $statement->bindParam(":last_name", $this->last_name);
        $statement->bindParam(":email", $this->email);
        return $statement->execute();
    }


    public function addOnGoingSeries(int $id)
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select * from ongoing_series where email = ? and id = ?";
        $statement = $pdo->prepare($query);

        $statement->bindParam(1, $this->email);
        $statement->bindParam(2, $id);
        $statement->execute();

        $result = $statement->rowCount();

        if ($result > 0) return; // already ongoing, no need to add

        $query = "insert into ongoing_series (email, id) values (?, ?)";
        $statement = $pdo->prepare($query);
        $statement->bindParam(1, $this->email);
        $statement->bindParam(2, $id);
        $statement->execute();
    }

    public function getSeries(string $q): array
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->prepare($q);
        $statement->bindParam(1, $this->email);
        $statement->execute();
        $result = $statement->fetchAll();
        $series = [];
        foreach ($result as $serie) {
            $series[] = Serie::find($serie['id']);
        }
        return $series;
    }

    public function getOnGoingSeries(): array
    {
        $str = "select * from ongoing_series where email = ?";
        return $this->getSeries($str);
    }

    public function getFavoriteSeries(): array
    {
        $str = "select * from favorite_series where email = ?";
        return $this->getSeries($str);
    }

    static function isFavorite(int $i): bool
    {
        $pdo = ConnectionFactory::getConnection();
        $query = "select * from favorite_series where email=? and id=?";
        $statement = $pdo->prepare($query);
        $email = $_SESSION['user']->email;
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $i);
        $statement->execute();
        $result = $statement->rowCount();
        return $result > 0;
    }

    public function getComment(int $id): ?Review
    {
        $pdo = ConnectionFactory::getConnection();
        $query = "select * from reviews where id = :saison_id and email = :email";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':saison_id', $id);
        $statement->bindParam(':email', $_SESSION['user']->email);
        $statement->execute();
        $object = $statement->fetchObject(Review::class);
        return ($statement->rowCount() > 0) ? $object : null;
    }


}