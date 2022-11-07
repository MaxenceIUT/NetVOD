<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\LogoutAction;
use iutnc\deefy\action\SigninAction;

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
                <link rel="stylesheet" href="/assets/css/style.css">
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