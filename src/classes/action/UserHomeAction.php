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

            // TODO: Afficher les séries en cours de visionnage
            $arrayOnGoing = $user->getOnGoingSeries();
            foreach ($arrayOnGoing as $serie) {
                $html .= <<<END
                    <div class="serie">
                        <li><a href="index.php?action=show-serie-details&id=$serie->id">$serie->titre</a></li>
                        <img src="$serie->image" alt="Image de la série $serie->titre">
                    </div>  
                END;
            }
            $html .= <<<END
                    </div>
                </div>
                <div class="favorites">
                    <h3>Favoris</h3>
                    <div class="items">
            END;

            // TODO: Afficher les séries favorites
            $arrayOnGoing = $user->getFavoriteSeries();
            foreach ($arrayOnGoing as $serie) {
                $html .= <<<END
                    <div class="serie">
                        <li><a href="index.php?action=show-serie-details&id=$serie->id">$serie->titre</a></li>
                        <img src="$serie->image" alt="Image de la série $serie->titre">
                    </div>  
                END;
            }

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

    public function shouldBeAuthenticated(): bool
    {
        return true;
    }

}