<?php
class Player {
    private $name;
    private $score = 0;
    private $personalBest;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function getScore() {
        return $this->score;
    }

    public function addPoint() {
        $this->score++;
    }

    public function setPersonalBest($score) {
        $this->personalBest = max($this->personalBest, $score);
    }

    public function getPersonalBest() {
        return $this->personalBest;
    }
}
?>