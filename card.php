<?php
class Card {
    private $id;
    private $value;
    private $imagePath; // Nouvelle propriété pour stocker le chemin de l'image

    public function __constvruct($id, $value, $imagePath) {
        $this->id = $id;
        $this->value = $value;
        $this->imagePath = $imagePath; // Initialiser l'image
    }

    public function getId() {
        return $this->id;
    }

    public function getValue() {
        return $this->value;
    }

    public function getImagePath() {
        return $this->imagePath;
    }
}
?>
