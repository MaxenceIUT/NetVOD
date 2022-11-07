<?php

namespace iutnc\netvod\dispatch;

use iutnc\netvod\action\ShowSeriesDetailsAction;

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
                $action = new \iutnc\netvod\action\ViewSerieAction();
                $html = $action->execute();
                break;
            case 'show-series-details':
                $action = new ShowSeriesDetailsAction();
                $html = $action->execute();
                break;
            default:
                $html = "<h1>Bienvenue !</h1>";
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
        END;

        $htmlString .= $html;

        $htmlString .= <<<END
            </body>
        </html>
        END;

        echo $htmlString;
    }

}