<?php

namespace iutnc\netvod\data;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\users\User;
use PDO;

class Series
{
    protected int $id;
    protected string $titre, $descriptif, $date_ajout, $annee;
    protected string $image = "";

    /**
     * Method to find all series of the database
     * @return array Array of Series
     */
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

    /**
     * Method to find a serie by id (like a constructor with id)
     * @param int $id Serie id
     * @return Series Serie object
     */
    public static function find(int $id): Series
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "SELECT * FROM series WHERE id = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(":id", $id);
        $statement->execute();
        return $statement->fetchObject(Series::class);
    }


    /**
     * Method to return if a user bookmarked a serie
     * @param User $user User object
     * @return bool True if bookmarked, false otherwise
     */
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

    /**
     * @return int the score of the series, or -1 if no user has reviewed it
     */
    public function getScore(): int
    {
        $pdo = ConnectionFactory::getConnection();
        $query = "select avg(score) as score from series_reviews where id = ?";
        $statement = $pdo->prepare($query);
        $statement->bindParam(1, $this->id);
        $statement->execute();
        $result = $statement->fetch();

        return $result['score'] ?? -1;
    }

    /**
     * Method to get the reviews of the serie
     * @return array Array of Review
     */
    public function getReviews(): array
    {
        $pdo = ConnectionFactory::getConnection();
        $query = "select * from series_reviews where id = ?";
        $statement = $pdo->prepare($query);
        $statement->bindParam(1, $this->id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS, Review::class);
    }

    /**
     * Get the id of the first and the last episode of the serie
     * @return array the id of the first and the last episode
     */
    public function getBorneEp(): array
    {
        $pdo = ConnectionFactory::getConnection();
        $sql = "select min(id), max(id) from episode where serie_id = ?";
        $stmt = $pdo->prepare($sql);
        $idSerie = $this->series->id;
        $stmt->bindParam(1, $idSerie);
        $stmt->execute();
        $result = $stmt->fetch();
        return ['min' => $result[0], 'max' => $result[1]];
    }

    public function __get($name)
    {
        return $this->$name;
    }

}