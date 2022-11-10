<?php

namespace iutnc\netvod\action\account;

use iutnc\netvod\action\Action;
use iutnc\netvod\action\series\DisplayAlreadySeenAction;
use iutnc\netvod\action\series\DisplayBookmarkedSeries;
use iutnc\netvod\action\series\DisplayOngoingSeries;
use iutnc\netvod\auth\Auth;

class UserHomeAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            $user = Auth::getCurrentUser();

            $html = <<<END
            <section>
                <img class="background" src='assets/img/background.jpg' alt='Background' />
                <h1>Bonjour $user->first_name 👋</h1>
                <h2>Qu'est ce qui vous ferait plaisir aujourd'hui ?</h2>
            </section>
            <main>
            END;

            $displayOnGoingSeries = new DisplayOnGoingSeries();
            $html .= $displayOnGoingSeries->execute();

            $displayBookmarkedSeries = new DisplayBookmarkedSeries();
            $html .= $displayBookmarkedSeries->execute();

            $DisplayAlreadySeeAction = new DisplayAlreadySeenAction();
            $html .= $DisplayAlreadySeeAction->execute();

            $html .= <<<END
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