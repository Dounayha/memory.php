<?php
class Leaderboard {
    private $topPlayers;

    public function __construct() {
        $this->topPlayers = [];
    }

    public function updateLeaderboard($player) {
        $this->topPlayers[$player->getName()] = $player->getBestScores()[0];
        arsort($this->topPlayers); // Trie les joueurs par leur meilleur score en ordre décroissant

        if (count($this->topPlayers) > 10) {
            array_pop($this->topPlayers); // Garde seulement les 10 meilleurs joueurs
        }
    }

    public function getTopPlayers() {
        return $this->topPlayers;
    }
}
?>
