<?php

namespace iutnc\netvod\dispatch;

use iutnc\netvod\action\AccountAction;
use iutnc\netvod\action\LandingPageAction;
use iutnc\netvod\action\LoginAction;
use iutnc\netvod\action\LogoutAction;
use iutnc\netvod\action\RegisterAction;
use iutnc\netvod\action\ShowEpisodeDetailsAction;
use iutnc\netvod\action\ShowSeriesDetailsAction;
use iutnc\netvod\action\ViewSerieAction;

class Dispatcher
{

    protected ?string $action;

    public function __construct()
    {
        $this->action = $_GET['action'];
    }

    public function run(): void
    {
        switch ($this->action) {
            case 'view-serie':
                $action = new ViewSerieAction();
                $html = $action->execute();
                break;
            case 'show-series-details':
                $action = new ShowSeriesDetailsAction();
                $html = $action->execute();
                break;
            case 'show-episode-details':
                $action = new ShowEpisodeDetailsAction();
                $html = $action->execute();
                break;
            case 'login':
                $action = new LoginAction();
                $html = $action->execute();
                break;
            case 'register':
                $action = new RegisterAction();
                $html = $action->execute();
                break;
            case 'logout':
                $action = new LogoutAction();
                $html = $action->execute();
                break;
            case 'account':
                $action = new AccountAction();
                $html = $action->execute();
                break;
            default:
                $action = new LandingPageAction();
                $html = $action->execute();
                break;
        }
        $this->renderPage($html);
    }

    public function renderPage(string $html): void
    {
        $htmlString = <<<END
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>NetVOD</title>
                <link rel="stylesheet" href="assets/css/style.css">
            </head>
            <body>
                <header>
                    <span>NetVOD</span>
                </header>
        END;

        $htmlString .= $html;

        $htmlString .= <<<END
            </body>
        </html>
        END;

        echo $htmlString;
    }

}