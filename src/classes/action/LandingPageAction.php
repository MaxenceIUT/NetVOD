<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;

class LandingPageAction extends Action
{

    public function execute(): string
    {
        $user = Auth::getCurrentUser();
        $html = <<<END
        <h1>Bienvenue sur NetVOD</h1>
        <h2>NetVOD est la plateforme de SVOD la plus géniale du monde</h2>
        END;

        if ($user == null) {
            $html .= <<<END
            <a href="index.php?action=login">Se connecter</a>
            <a href="index.php?action=register">S'inscrire</a>
            END;
        } else {
            $html .= <<<END
            <div class="account">
                <p>Bonjour $user->first_name 👋</p>
                <a href="index.php?action=home">Mon compte</a>
                <a href="index.php?action=logout">Se déconnecter</a>
            </div>
            END;
        }
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