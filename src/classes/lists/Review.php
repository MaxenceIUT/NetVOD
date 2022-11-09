<?php

namespace iutnc\netvod\lists;

use iutnc\netvod\db\ConnectionFactory;

class Review
{
    protected string $email, $comment;
    protected int $id, $score;


    public function __construct()
    {

    }

    public static function create(string $email, int $id, int $note, string $comment): Review
    {
        $review = new Review();
        $review->email = $email;
        $review->id = $id;
        $review->comment = $comment;
        $review->score = $note;
        return $review;
    }

    public function addBase(): bool
    {
        $pdo = ConnectionFactory::getConnection();
        $query = "insert into reviews(email, id, comment, score) values (?,?,?,?)";
        $statement = $pdo->prepare($query);
        $statement->bindParam(1, $this->email);
        $statement->bindParam(2, $this->id);
        $statement->bindParam(3, $this->comment);
        $statement->bindParam(4, $this->score);
        $statement->execute();
        return true;
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