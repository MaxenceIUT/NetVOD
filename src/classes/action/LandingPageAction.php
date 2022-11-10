<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;

class LandingPageAction extends Action
{

    public function execute(): string
    {
        $user = Auth::getCurrentUser();
        $html = <<<END
        <div class="fullscreen-background">
            <img width="100%" height="100%" src="assets/img/background.jpg" alt="Background" />
        </div>
        <div class="hero">
            <div class="hero-content">
                <h1>Bienvenue sur NetVOD</h1>
                <div class="details">
                    <h2>NetVOD est la plateforme de SVOD la plus géniale du monde</h2>
                </div>
            </div>
        </div>
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