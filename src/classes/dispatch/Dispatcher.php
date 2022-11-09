<?php

namespace iutnc\netvod\dispatch;

use iutnc\netvod\action\account\AccountAction;
use iutnc\netvod\action\account\LoginAction;
use iutnc\netvod\action\account\LogoutAction;
use iutnc\netvod\action\account\RegisterAction;
use iutnc\netvod\action\account\UserHomeAction;
use iutnc\netvod\action\Action;
use iutnc\netvod\action\api\AddFavoriteSerieAction;
use iutnc\netvod\action\api\RemoveFavoriteSerieAction;
use iutnc\netvod\action\LandingPageAction;
use iutnc\netvod\action\series\DisplayFavoritesSeriesAction;
use iutnc\netvod\action\series\ShowEpisodeDetailsAction;
use iutnc\netvod\action\series\ShowReviewsAction;
use iutnc\netvod\action\series\ShowSerieDetailsAction;
use iutnc\netvod\action\series\ViewSerieAction;

class Dispatcher
{

    protected ?string $action;
    protected array $actions;

    public function __construct()
    {
        $this->action = $_GET['action'];

        $this->registerActions(
            new LandingPageAction(),
            new RegisterAction(),
            new LoginAction(),
            new LogoutAction(),
            new AccountAction(),
            new UserHomeAction(),
            new ViewSerieAction(),
            new AddFavoriteSerieAction(),
            new RemoveFavoriteSerieAction(),
            new DisplayFavoritesSeriesAction(),
            new ShowEpisodeDetailsAction(),
            new ShowSerieDetailsAction(),
            new ShowReviewsAction()
        );
    }

    public function run(): void
    {
        if ($this->action == null) {
            $html = $this->actions['landing-page']->execute();
        } else {
            if (isset($this->actions[$this->action])) {
                $action = $this->actions[$this->action];
                if ($action->shouldBeAuthenticated() && !isset($_SESSION['user'])) {
                    http_response_code(403);
                    $html = <<<END
                    <h1>Eh mais, vous n'avez rien à faire là !</h1>
                    <p>Vous devez être connecté pour accéder à cette page</p>
                    <a href="index.php?action=login">Se connecter</a>
                    END;
                } else {
                    $html = $action->execute();
                }
            } else {
                http_response_code(404);
                $html = <<<END
                <h1>Page introuvable</h1>
                <p>La page que vous recherchez n'existe pas</p>
                END;
            }
        }
        $this->renderPage($html);
    }

    public function renderPage(string $html): void
    {
        $htmlString = <<<END
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF - 8">
                <title>NetVOD</title>
                <link rel="stylesheet" href="assets / css / style . css">
            </head>
            <body>
        END;

        $htmlString .= $html;

        $htmlString .= <<<END
            </body>
        </html>
        END;

        echo $htmlString;
    }

    private function registerAction(Action $action): void
    {
        $this->actions[$action->getActionName()] = $action;
    }

    private function registerActions(Action...$action): void
    {
        foreach ($action as $a) {
            $this->registerAction($a);
        }
    }

}