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
    protected bool $activated;

    /**
     * Get the user object from its email
     * @param string $email The user email
     * @return User|null The user object if found, null otherwise
     */
    public static function find(string $email): ?User
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindParam(":email", $email);

        if ($statement->execute()) {
            return $statement->fetchObject(User::class);
        }
        return null;
    }

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
        }
    }

    public function save(): bool
    {
        $pdo = ConnectionFactory::getConnection();
        $statement = $pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, favorite_genre = :favorite_genre, activated = :activated WHERE email = :email");
        $statement->bindParam(":first_name", $this->first_name);
        $statement->bindParam(":last_name", $this->last_name);
        $statement->bindParam(":favorite_genre", $this->favorite_genre);
        $statement->bindParam(":activated", $this->activated);
        $statement->bindParam(":email", $this->email);
        return $statement->execute();
    }

    /**
     * Method to add an episode to the watched list of the user
     * @param Episode $episode Episode to add in watched db
     * @return void
     */
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

    /**
     * Method to refactor getSeries to bookmarked series and ongoing series
     * @param string $table table to get the series from (with joiture if needed)
     * @param string $select select episod or serie id
     * @return array of Series
     */
    private function getSeries(string $table, string $select): array
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select * from series where id IN (select " . $select . " from " . $table . " where email = ?)";
        $statement = $pdo->prepare($query);
        $statement->bindParam(1, $this->email);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, Series::class);
    }

    /**
     * Method to refactor getSeries
     * @return array with param to use getSeries method to ongoing series
     */
    public function getOngoingSeries(): array
    {
        $table = "watched_episodes inner join episode e on watched_episodes.id = e.id";
        return $this->getSeries($table, "serie_id");
    }

    /**
     * @return array with param to use getSeries method to bookmarked series
     */
    public function getBookmarkedSeries(): array
    {
        return $this->getSeries("bookmarked_series", "id");
    }

    /**
     * Method to return the comment of the user for a series
     * @param int $id id of the series
     * @return Review|null if he commented the series, return the review, else return null
     */
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

    public function generateActivationToken(): string
    {
        $pdo = ConnectionFactory::getConnection();

        $token = bin2hex(random_bytes(64));
        $tokenExpiration = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        $query = "insert into activation_tokens (email, token, token_expiration) values (:email, :token, :expiration)";
        $statement = $pdo->prepare($query);

        $statement->bindParam(':email', $this->email);
        $statement->bindParam(':token', $token);
        $statement->bindParam(':expiration', $tokenExpiration);

        $statement->execute();

        return $token;
    }

    public function checkActivationToken(string $token): bool
    {
        $pdo = ConnectionFactory::getConnection();

        $query = "select * from activation_tokens where email = :email and token = :token and token_expiration > :now";
        $statement = $pdo->prepare($query);

        $now = date("Y-m-d H:i:s");
        $statement->bindParam(':email', $this->email);
        $statement->bindParam(':token', $token);
        $statement->bindParam(':now', $now);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $this->activated = true;
            $this->save();

            $query = "delete from activation_tokens where email = :email";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':email', $this->email);
            $statement->execute();

            return true;
        }

        return false;
    }

}