<?php

namespace iutnc\netvod\lists;

use iutnc\netvod\db\ConnectionFactory;

class Serie
{
    protected int $id;
    protected string $titre, $descriptif;
    protected string $image;
    protected string $dateAjout;
    protected string $annee;

    public function __construct(int $id)
    {
        $this->id = $id;
        $pdo = ConnectionFactory::getConnection();
        $query = "SELECT * FROM serie WHERE id = :id";
        $statement = $pdo->prepare($query);
        $statement->execute(['id' => $id]);
        $serie = $statement->fetch();
        $this->titre = $serie['titre'];
        $this->descriptif = $serie['descriptif'];
        $this->image = $serie['img'];
        $this->dateAjout = $serie['date_ajout'];
        $this->annee = $serie['annee'];
    }

    public function __get($name)
    {
        return $this->$name;
    }

}