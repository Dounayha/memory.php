<?php
require_once 'game.php';
require_once 'leaderboard.php';

$game = new Game(5); // Crée un jeu avec 5 paires
$leaderboard = new Leaderboard();

// Ajoute des joueurs
$game->addPlayer('Alice');
$game->addPlayer('Bob');

// Exemple de jeu
$game->playTurn(0, 1);
$game->playTurn(2, 3);

// Mise à jour du classement
foreach ($game->players as $player) {
    $leaderboard->updateLeaderboard($player);
}

// Affiche le classement
print_r($leaderboard->getTopPlayers());
?>
