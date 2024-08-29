<?php
require_once 'Card.php';
require_once 'Player.php';
require_once 'Leaderboard.php';

// Créer des cartes avec des images
$card1 = new Card(1, 'A', './assets/images/aurore.png');
$card2 = new Card(2, 'A', './assets/images/aurore.png');
$card3 = new Card(3, 'B', './assets/images/blancheneige.png');
$card4 = new Card(4, 'B', './assets/images/blancheneige.png');
$card5= new Card(5, 'C', './assets/images/raiponce.png');
$card6 = new Card(6, 'C', './assets/images/raiponce.png');


// Créer des joueurs
$player1 = new Player('Alice');
$player2 = new Player('Bob');

// Simuler des scores
$player1->addScore(100);
$player2->addScore(150);
$player1->addScore(200);

// Créer un classement
$leaderboard = new Leaderboard();
$leaderboard->updateLeaderboard($player1);
$leaderboard->updateLeaderboard($player2);

// Afficher le classement des 10 meilleurs joueurs
echo "<h2>Classement des meilleurs joueurs</h2>";
print_r($leaderboard->getTopPlayers());

// Afficher les cartes avec les images
echo "<h2>Cartes du jeu</h2>";
$cards = [$card1, $card2, $card3, $card4, $card5, $card6];
foreach ($cards as $card) {
    echo '<div style="display: inline-block; margin: 10px;">';
    echo '<img src="' . $card->getImagePath() . '" alt="Card Image" style="width: 100px; height: 150px;">';
    echo '<p>Valeur: ' . $card->getValue() . '</p>';
    echo '</div>';
}
?>
