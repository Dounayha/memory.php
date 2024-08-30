<?php
require_once 'card.php';
require_once 'player.php';

class Game {
    private $cards;
    public $players;
    private $currentPlayer;
    private $pairsFound;

    public function __construct($numberOfPairs) {
        if ($numberOfPairs < 3) $numberOfPairs = 3; // Minimum 3 paires
        if ($numberOfPairs > 12) $numberOfPairs = 12; // Maximum 12 paires

        $this->cards = [];
        $this->players = [];
        $this->pairsFound = 0;

        // Chemins d'images pour chaque paire
        $imagePaths = [
            './assets/images/aurore.png',
            './assets/images/blancheneige.png',
            './assets/images/raiponce.png',
            
        ];

        // Crée les cartes avec des images
        for ($i = 1; $i <= $numberOfPairs; $i++) {
            $imagePath = $imagePaths[($i - 1) % count($imagePaths)]; // Choisir une image aléatoire pour chaque paire
            $this->cards[] = new Card($i, chr(64 + $i), $imagePath); // Crée une carte avec un caractère unique (A, B, C, etc.)
            $this->cards[] = new Card($i, chr(64 + $i), $imagePath); // Deuxième carte de la paire
        }

        shuffle($this->cards); // Mélange les cartes
    }

    public function addPlayer($playerName) {
        $player = new Player($playerName);
        $this->players[] = $player;
        if (count($this->players) === 1) {
            $this->currentPlayer = $player;
        }
    }

    public function getCards() {
        return $this->cards;
    }

    public function playTurn($cardIndex1, $cardIndex2) {
        if ($cardIndex1 === $cardIndex2 || $cardIndex1 < 0 || $cardIndex2 < 0 || $cardIndex1 >= count($this->cards) || $cardIndex2 >= count($this->cards)) {
            return false; // Vérifie les indices valides
        }

        $card1 = $this->cards[$cardIndex1];
        $card2 = $this->cards[$cardIndex2];

        if ($card1->getId() === $card2->getId()) {
            $this->pairsFound++;
            $this->currentPlayer->addScore(10); // Ajoute 10 points pour une paire trouvée
            return true;
        } else {
            return false;
        }
    }

    public function isGameOver() {
        return $this->pairsFound >= count($this->cards) / 2;
    }
}

?>
