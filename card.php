<?php
class Card {
    private $id;
    private $isVisible;

    public function __construct($id) {
        $this->id = $id;
        $this->isVisible = false;  // Par défaut, les cartes sont cachées
    }

    public function getId() {
        return $this->id;
    }

    public function flip() {
        $this->isVisible = !$this->isVisible;  // Inverse le statut de visibilité
    }

    public function isVisible() {
        return $this->isVisible;
    }
}
?>
