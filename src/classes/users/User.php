<?php

namespace iutnc\netvod\users;

use InvalidArgumentException;

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

}