<?php
require_once 'Card.php';
require_once 'Player.php';
require_once 'interface.php';

class Game {
    private $cards = [];
    private $numPairs;
    private $players = [];
    private $currentPlayerIndex = 0;

    public function __construct($numPairs) {
        $this->numPairs = $numPairs;
        $this->generateCards();
        $this->shuffleCards();
    }

    private function generateCards() {
        for ($i = 1; $i <= $this->numPairs; $i++) {
            $this->cards[] = new Card($i * 2 - 1, $i);
            $this->cards[] = new Card($i * 2, $i);
        }
    }

    private function shuffleCards() {
        shuffle($this->cards);
    }

    public function getCards() {
        return $this->cards;
    }

    public function addPlayer($name) {
        $this->players[] = new Player($name);
    }

    public function getCurrentPlayer() {
        return $this->players[$this->currentPlayerIndex];
    }

    public function nextPlayer() {
        $this->currentPlayerIndex = ($this->currentPlayerIndex + 1) % count($this->players);
    }

    public function checkPair($card1, $card2) {
        if ($card1->isMatch($card2)) {
            $this->getCurrentPlayer()->addPoint();
            return true;
        } else {
            $this->nextPlayer();
            return false;
        }
    }

    public function getLeaderboard() {
        usort($this->players, function($a, $b) {
            return $b->getScore() - $a->getScore();
        });
        return array_slice($this->players, 0, 10);
    }
}
?>