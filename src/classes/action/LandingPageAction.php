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
        END;
        return $html;
    }

}