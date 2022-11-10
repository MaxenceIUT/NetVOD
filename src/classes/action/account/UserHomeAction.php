<?php

namespace iutnc\netvod\action\account;

use iutnc\netvod\action\Action;
use iutnc\netvod\action\series\DisplayAlreadySeeAction;
use iutnc\netvod\action\series\DisplayOngoingSeries;
use iutnc\netvod\auth\Auth;
use iutnc\netvod\renderer\Renderer;
use iutnc\netvod\renderer\SeriesRenderer;

class UserHomeAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            $user = Auth::getCurrentUser();

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
            END;

            $displayOnGoingSeries = new DisplayOnGoingSeries();
            $html .= $displayOnGoingSeries->execute();
            $html .= <<<END
                <div class="bookmarked">
                    <h3>Revivre les meilleurs moments</h3>
                    <div class="items">
            END;

            $bookmarkedSeries = $user->getBookmarkedSeries();
            foreach ($bookmarkedSeries as $series) {
                $renderer = new SeriesRenderer($series);
                $html .= $renderer->render(Renderer::COMPACT);
            }

            $html .= <<<END
                    </div>
                </div>
            </main>

               
            END;

            $DisplayAlreadySeeAction = new DisplayAlreadySeeAction();
            $html .= $DisplayAlreadySeeAction->execute();


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