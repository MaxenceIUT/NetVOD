<?php

namespace iutnc\netvod\dispatch;

class Dispatcher
{

    protected ?string $action;
    protected array $actions;

    public function __construct()
    {
        $this->action = $_GET['action'];

        $actionsName = array_diff(scandir('src\classes\action'), array('..', '.', 'Action.php'));
        foreach ($actionsName as $actionName) {
            $actionName = str_replace('.php', '', $actionName);
            $actionClass = "iutnc\\netvod\\action\\$actionName";
            $createdAction = new $actionClass();
            $this->actions[$createdAction->getActionName()] = $createdAction;
        }
    }

    public function run(): void
    {
        if (isset($this->actions[$this->action])) {
            $action = $this->actions[$this->action];
            $html = $action->execute();
        } else {
            $action = $this->actions['landing-page'];
            $html = $action->execute();
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