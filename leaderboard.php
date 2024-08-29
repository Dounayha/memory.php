<?php
class Leaderboard {
    private $topPlayers;

    public function __construct() {
        $this->topPlayers = [];
    }

    public function updateLeaderboard($player) {
        $bestScores = $player->getBestScores(); // Récupère les meilleurs scores du joueur

        // Vérifie que le tableau des meilleurs scores n'est pas vide
        if (!empty($bestScores)) {
            $this->topPlayers[$player->getName()] = $bestScores[0];
        } else {
            // Si aucun score n'est disponible, on peut attribuer un score de 0 par défaut
            $this->topPlayers[$player->getName()] = 0;
        }

        // Trie les joueurs par leur meilleur score en ordre décroissant
        arsort($this->topPlayers);

        // Garde seulement les 10 meilleurs joueurs
        if (count($this->topPlayers) > 10) {
            array_pop($this->topPlayers);
        }
    }

    public function getTopPlayers() {
        return $this->topPlayers;
    }
}
?>
