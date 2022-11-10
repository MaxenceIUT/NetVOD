<?php

namespace iutnc\netvod\renderer;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\data\Episode;
use iutnc\netvod\data\Series;
use iutnc\netvod\db\ConnectionFactory;

class OngoingSeriesRenderer implements Renderer
{

    private Series $series;

    /**
     * @param Series $series
     */
    public function __construct(Series $series)
    {
        $this->series = $series;
    }

    /**
     * @param int $mode Render for a serie with a direct like to the next episode
     * @return string HTML
     */
    function render(int $mode): string
    {
        $lastEp = $this->lastEp();
        if ($lastEp == 0) $lastEp = 1;
        $user = Auth::getCurrentUser();
        $episode = Episode::find($lastEp);
        if ($user != null) {
            if (!$episode->isBookmarkedBy($user)) {
                $link = "index.php?action=show-episode-details&id={$lastEp}&bookmark=false";
            } else {
                $link = "index.php?action=show-episode-details&id={$lastEp}&bookmark=true";
            }
        }

        return <<<END
            <ul>
                <div class="serie">
                    <li><a href="$link">Reprendre {$this->series->titre}</a></li>
                    <img src="{$this->series->image}" alt="Image de la série {$this->series->titre}">
                </div>
            </ul>
            END;
    }

    /**
     * Get the number of the last episode seeing by the user
     * @return int the number of the last episode
     */
    function lastEp(): int
    {
        $pdo = ConnectionFactory::getConnection();
        $email = Auth::getCurrentUser()->email;

        $sql = "select * from watched_episodes inner join episode on watched_episodes.id = episode.id where email = ? and serie_id = ? and episode.id = ?";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(1, $email);
        $idSerie = $this->series->id;
        $stmt->bindParam(2, $idSerie);
        $nbEp = count($this->series->getAll());

        $borne = $this->series->getBorneEp();
        for ($i = $borne['min']; $i <= $borne['max']; $i++) {
            $stmt->bindParam(3, $i);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return $i;
            }
        }
        return 0;
    }


}