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

        $borne = $this->idMinMaxEpisode();
        echo "{$this->series->titre} borne inf" . $borne['min'] . " Borne sup " . $borne['max'] . "<br>";
        for ($i = $borne['min']; $i <= $borne['max']; $i++) {
            $stmt->bindParam(3, $i);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
//                return ($i > $borne['max']) ? 0 : $i + 1;
                return $i;
            }
        }
        return 0;
    }

    /**
     * Get the id of the first and the last episode of the serie
     * @return array the id of the first and the last episode
     */
    public function idMinMaxEpisode(): array
    {
        $pdo = ConnectionFactory::getConnection();
        $sql = "select min(id), max(id) from episode where serie_id = ?";
        $stmt = $pdo->prepare($sql);
        $idSerie = $this->series->id;
        $stmt->bindParam(1, $idSerie);
        $stmt->execute();
        $result = $stmt->fetch();
        return ['min' => $result[0], 'max' => $result[1]];
    }

}