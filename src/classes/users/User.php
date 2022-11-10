<?php

namespace iutnc\netvod\users;

use InvalidArgumentException;
use iutnc\netvod\data\Episode;
use iutnc\netvod\data\Review;
use iutnc\netvod\data\Series;
use iutnc\netvod\db\ConnectionFactory;
use PDO;

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
        $statement = $pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, favorite_genre = :favorite_genre WHERE email = :email");
        $statement->bindParam(":first_name", $this->first_name);
        $statement->bindParam(":last_name", $this->last_name);
        $statement->bindParam(":favorite_genre", $this->favorite_genre);
        $statement->bindParam(":email", $this->email);
        return $statement->execute();
    }


    public function addWatchedEpisode(Episode $episode): void
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select * from watched_episodes where email = ? and id = ?";
        $statement = $pdo->prepare($query);

        $email = $this->email;
        $id = $episode->id;

        $statement->bindParam(1, $email);
        $statement->bindParam(2, $id);
        $statement->execute();

        $result = $statement->rowCount();

        if ($result > 0) return; // already ongoing, no need to add

        $query = "insert into watched_episodes (email, id) values (?, ?)";
        $statement = $pdo->prepare($query);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $id);
        $statement->execute();
    }

    private function getSeries(string $table, string $select): array
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select * from series where id IN (select " . $select . " from " . $table . " where email = ?)";
        $statement = $pdo->prepare($query);
        $statement->bindParam(1, $this->email);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, Series::class);
    }

    public function getOngoingSeries(): array
    {
        $table = "watched_episodes inner join episode e on watched_episodes.id = e.id";
        return $this->getSeries($table, "serie_id");
    }

    public function getBookmarkedSeries(): array
    {
        return $this->getSeries("bookmarked_series", "id");
    }

    public function getComment(int $id): ?Review
    {
        $pdo = ConnectionFactory::getConnection();
        $query = "select * from series_reviews where id = :saison_id and email = :email";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':saison_id', $id);
        $statement->bindParam(':email', $this->email);
        $statement->execute();
        $object = $statement->fetchObject(Review::class);
        return ($statement->rowCount() > 0) ? $object : null;
    }

}