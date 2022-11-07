<?php

namespace iutnc\netvod\users;

use InvalidArgumentException;
use iutnc\netvod\db\ConnectionFactory;

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

}