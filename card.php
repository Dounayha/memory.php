<?php
class Card {
    private $id;      // Identifiant unique de la carte
    private $pairId;  // Identifiant de la paire

    public function __construct($id, $pairId) {
        $this->id = $id;
        $this->pairId = $pairId;
    }

    public function getId() {
        return $this->id;
    }

    public function getPairId() {
        return $this->pairId;
    }

    public function isMatch(Card $otherCard) {
        return $this->pairId === $otherCard->getPairId();
    }
}
?>