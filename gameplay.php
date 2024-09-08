<?php
session_start();
require_once 'Game.php';
require_once 'interface.php';

// Récupération du nombre de paires choisi par l'utilisateur, sinon utiliser une valeur par défaut
$pairCount = isset($_SESSION['pair_count']) ? $_SESSION['pair_count'] : 3;

// Initialisation du jeu si ce n'est pas encore fait
if (!isset($_SESSION['game'])) {
    $_SESSION['game'] = serialize(new Game($pairCount));
}

// Récupération de l'objet Game
$game = unserialize($_SESSION['game']);

// Gestion du clic sur une carte
if (isset($_POST['card_index'])) {
    $cardIndex = intval($_POST['card_index']);

    // Récupération des cartes
    $cards = $game->getCards();

    // Gestion des cartes retournées
    if (!isset($_SESSION['flipped_cards'])) {
        $_SESSION['flipped_cards'] = [];
    }

    // Stocker les cartes retournées
    $_SESSION['flipped_cards'][] = $cardIndex;

    if (count($_SESSION['flipped_cards']) == 2) {
        $firstCardIndex = $_SESSION['flipped_cards'][0];
        $secondCardIndex = $_SESSION['flipped_cards'][1];

        $firstCard = $cards[$firstCardIndex];
        $secondCard = $cards[$secondCardIndex];

        if ($game->checkPair($firstCard, $secondCard)) {
            // Les cartes correspondent
            $_SESSION['flipped_cards'] = []; // Réinitialiser les cartes retournées
        } else {
            // Les cartes ne correspondent pas
            $_SESSION['flipped_cards'] = []; // Réinitialiser les cartes retournées
            $game->nextPlayer();

            // Actualiser la session après tentative incorrecte
            $_SESSION['game'] = serialize($game);
        }
    }

    $_SESSION['game'] = serialize($game);
}

// Vérifier si le jeu est terminé
if (count(array_filter($_SESSION['flipped_cards'], function($index) use ($cards) {
    return $cards[$index]->isMatch($cards[$_SESSION['flipped_cards'][0]]);
})) === $pairCount) {
    echo "<h2>Félicitations ! Vous avez terminé le jeu avec " . $game->getCurrentPlayer()->getScore() . " points.</h2>";
    session_destroy(); // Réinitialiser la session pour un nouveau jeu
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Memory</title>
    <style>
        .game-board {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
            max-width: 600px;
            margin: 0 auto;
        }
        .card {
            width: 100px;
            height: 150px;
            background-color: #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            cursor: pointer;
            border: 1px solid #000;
            transition: background-color 0.5s;
        }
        .flipped {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Jeu de Memory</h1>
        <h3>Paires trouvées : <?= count(array_filter($_SESSION['flipped_cards'], function($index) use ($cards) {
            return $cards[$index]->isMatch($cards[$_SESSION['flipped_cards'][0]]);
        })) ?>/<?= $pairCount ?></h3>
        <h3>Nombre d'essais : <?= $_SESSION['attempts'] ?></h3>

        <div class="game-board">
            <?php foreach ($game->getCards() as $index => $card): ?>
                <form method="post" style="display:inline-block;">
                    <button type="submit" name="card_index" value="<?= $index ?>" class="card <?= in_array($index, $_SESSION['flipped_cards'] ?? []) ? 'flipped' : '' ?>">
                        <?= in_array($index, $_SESSION['flipped_cards'] ?? []) ? $card->getPairId() : '' ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>