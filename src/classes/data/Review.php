<?php

namespace iutnc\netvod\data;

use iutnc\netvod\db\ConnectionFactory;

class Review
{
    protected string $email, $comment;
    protected int $id, $score;

    /**
     * Method to create a serie review (like a constructor)
     * @param string $email User email
     * @param int $id Serie id
     * @param int $note Score
     * @param string $comment Comment
     * @return Review Review object
     */
    public static function create(string $email, int $id, int $note, string $comment): Review
    {
        $review = new Review();
        $review->email = $email;
        $review->id = $id;
        $review->comment = $comment;
        $review->score = $note;
        return $review;
    }

    /**
     * Method to add a review in the database
     */
    public function addBase()
    {
        $pdo = ConnectionFactory::getConnection();
        $query = "insert into series_reviews (email, id, comment, score) values (?, ?, ?, ?)";
        $statement = $pdo->prepare($query);
        $statement->bindParam(1, $this->email);
        $statement->bindParam(2, $this->id);
        $statement->bindParam(3, $this->comment);
        $statement->bindParam(4, $this->score);
        $statement->execute();
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }


}