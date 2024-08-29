<?php
class Player {
    private $name;
    private $bestScores;
    private $currentScore;

    public function __construct($name) {
        $this->name = $name;
        $this->bestScores = [];
        $this->currentScore = 0;
    }

    public function getName() {
        return $this->name;
    }

    public function addScore($score) {
        $this->currentScore = $score;
        array_push($this->bestScores, $score);
        rsort($this->bestScores); // Trie les scores en ordre décroissant
        if (count($this->bestScores) > 10) {
            array_pop($this->bestScores); // Garde seulement les 10 meilleurs scores
        }
    }

    public function getBestScores() {
        return $this->bestScores;
    }

    public function getCurrentScore() {
        return $this->currentScore;
    }
}
?>
