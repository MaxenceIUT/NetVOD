<?php

namespace iutnc\netvod\dispatch;

use iutnc\netvod\action\account\AccountAction;
use iutnc\netvod\action\account\ActivateAccountAction;
use iutnc\netvod\action\account\LoginAction;
use iutnc\netvod\action\account\LogoutAction;
use iutnc\netvod\action\account\PasswordForget;
use iutnc\netvod\action\account\RegisterAction;
use iutnc\netvod\action\account\ResetPassword;
use iutnc\netvod\action\account\UserHomeAction;
use iutnc\netvod\action\Action;
use iutnc\netvod\action\api\AddFavoriteSerieAction;
use iutnc\netvod\action\api\RemoveFavoriteSerieAction;
use iutnc\netvod\action\LandingPageAction;
use iutnc\netvod\action\series\DisplayAlreadySeenAction;
use iutnc\netvod\action\series\DisplayFavoritesSeriesAction;
use iutnc\netvod\action\series\ShowEpisodeDetailsAction;
use iutnc\netvod\action\series\ShowReviewsAction;
use iutnc\netvod\action\series\ShowSerieDetailsAction;
use iutnc\netvod\action\series\ViewSeriesAction;
use iutnc\netvod\auth\Auth;

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
            new ViewSeriesAction(),
            new AddFavoriteSerieAction(),
            new RemoveFavoriteSerieAction(),
            new DisplayFavoritesSeriesAction(),
            new ShowEpisodeDetailsAction(),
            new ShowSerieDetailsAction(),
            new ShowReviewsAction(),
            new PasswordForget(),
            new ResetPassword(),
            new ShowReviewsAction(),
            new ActivateAccountAction(),
            new DisplayAlreadySeenAction()
        );
    }

    private function registerActions(Action...$action): void
    {
        foreach ($action as $a) {
            $this->registerAction($a);
        }
    }

    private function registerAction(Action $action): void
    {
        $this->actions[$action->getActionName()] = $action;
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
                    <h1>Eh mais, vous n'avez rien ?? faire l?? !</h1>
                    <p>Vous devez ??tre connect?? pour acc??der ?? cette page</p>
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
        $header = $this->generateHeader();

        $htmlString = <<<END
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="assets/css/style.css">
                <title>NetVOD</title>
            </head>
            <body>
                <header>
                    $header                
                </header>
                <div class="container">
                    $html
                </div>
                <footer>
                    <p>&copy; 2022 ??? BAUBY Gaspard, HOLDER Jules, PETIT Maxence</p>
                </footer>
            </body>
        </html>
        END;

        echo $htmlString;
    }

    private function generateHeader(): string
    {
        $user = Auth::getCurrentUser();
        $header = <<<END
            <div class="logo">
                <a href="index.php">NetVOD</a>            
            </div>
            <div class="menu">
        END;

        if ($user == null) {
            $header .= <<<END
                <a class="button-link button-link__text" href="index.php?action=login">Se connecter</a>
                <a class="button-link button-link__plain" href="index.php?action=register">S'inscrire</a>
            END;
        } else {
            $header .= <<<END
                <a class="button-link button-link__text" href="index.php?action=home">Accueil</a>
                <a class="button-link button-link__text" href="index.php?action=account">Mon compte</a>
                <a class="button-link button-link__text" href="index.php?action=view-series">Consulter le catalogue</a>
                <a class="button-link button-link__red button-link__text" href="index.php?action=logout">Se d??connecter</a>
            END;
        }

        return $header;
    }

}