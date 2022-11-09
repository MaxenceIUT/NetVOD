<?php

namespace iutnc\netvod\action;

class LandingPageAction extends Action
{

    public function execute(): string
    {
        $html = <<<END
        <h1>Bienvenue sur NetVOD</h1>
        <p>NetVOD est la plateforme de SVOD la plus géniale du monde</p>
        <a href="index.php?action=login">Se connecter</a>
        <a href="index.php?action=register">S'inscrire</a>
        END;
        return $html;
    }

    public function getActionName(): string
    {
        return "landing-page";
    }

    public function shouldBeAuthenticated(): bool
    {
        return false;
    }
}