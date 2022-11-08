<?php

namespace iutnc\netvod\action;

class UserHomeAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            $user = $_SESSION['user'];

            $html = <<<END
            <header>
                <a href="index.php?action=logout">Déconnexion</a>
                <a href="index.php?action=account">Gérer mon compte</a>            
            </header>
            <section>
                <h1>Bonjour $user->first_name 👋</h1>
                <h2>Qu'est ce qui vous ferait plaisir aujourd'hui ?</h2>            
            </section>
            <main>
                <div class="continue-watching">
                    <h3>Continuer à regarder</h3>
                    <div class="items">
            END;


            $html .= <<<END
                    </div>
                </div>
            </main>
            END;


            return $html;
        } else {
            return "Méthode non autorisée";
        }
    }

    public function getActionName(): string
    {
        return "home";
    }

}