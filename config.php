<?php
session_start();

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=memory_game', 'root', '');

// Initialisation de la partie si elle n'existe pas
if (!isset($_SESSION['game'])) {
    // Chemins des images
    $images = [
        './assets/images/aurore.png',
        './assets/images/blancheneige.png',
        './assets/images/raiponce.png',
        './assets/images/jasmine.png',
        './assets/images/reinedesneiges.png',
        './assets/images/sofia.png',
    ];

    // Double les images pour créer des paires
    $cards = array_merge($images, $images);

    // Mélange les cartes
    shuffle($cards);

    // Stocke l'état du jeu dans la session
    $_SESSION['game'] = [
        'cards' => $cards,
        'flipped' => [], // Cartes retournées temporairement
        'matched' => [], // Cartes définitivement retournées
        'score' => 0, // Score du joueur
    ];
}

// Réinitialiser le message de fin
$message = "";

// Gérer le retournement des cartes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_index'])) {
    $index = $_POST['card_index'];

    // Ajouter l'index de la carte retournée temporairement
    if (!in_array($index, $_SESSION['game']['matched']) && !in_array($index, $_SESSION['game']['flipped'])) {
        $_SESSION['game']['flipped'][] = $index;
    }

    // Vérifier si deux cartes sont retournées
    if (count($_SESSION['game']['flipped']) == 2) {
        // Obtenir les deux cartes
        $firstIndex = $_SESSION['game']['flipped'][0];
        $secondIndex = $_SESSION['game']['flipped'][1];

        // Vérifier si les cartes correspondent
        if ($_SESSION['game']['cards'][$firstIndex] == $_SESSION['game']['cards'][$secondIndex]) {
            // Ajouter les cartes aux correspondances définitives
            $_SESSION['game']['matched'][] = $firstIndex;
            $_SESSION['game']['matched'][] = $secondIndex;

            // Ajouter des points pour une paire correcte
            $_SESSION['game']['score'] += 10;
        } else {
            // Soustraire des points pour une paire incorrecte
            $_SESSION['game']['score'] -= 2;
        }

        // Réinitialiser les cartes retournées temporairement
        $_SESSION['game']['flipped'] = [];
    }
}

// Sauvegarder le score et réinitialiser la partie
if (count($_SESSION['game']['matched']) == count($_SESSION['game']['cards'])) {
    $playerName = $_SESSION['player_name'] ?? 'Joueur Anonyme';
    $stmt = $pdo->prepare("INSERT INTO scores (player_name, score) VALUES (?, ?)");
    $stmt->execute([$playerName, $_SESSION['game']['score']]);
    $message = "Partie terminée ! Score enregistré : " . $_SESSION['game']['score'] . " points.";
    // Réinitialiser la partie
    unset($_SESSION['game']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <link rel="stylesheet" href="./assets/images/css/style.css">
</head>
<body>

<h2>Jeu de Memory</h2>

<p><?php echo $message; ?></p>

<div class="card-container">
    <?php
    // Afficher les cartes
    foreach ($_SESSION['game']['cards'] as $index => $image) {
        $isFlipped = in_array($index, $_SESSION['game']['flipped']) || in_array($index, $_SESSION['game']['matched']);
        $cardClass = $isFlipped ? 'flipped' : '';
        ?>
        <div class="card">
            <form method="post" style="display:inline;">
                <input type="hidden" name="card_index" value="<?php echo $index; ?>">
                <button type="submit" class="card-button">
                    <div class="card-inner <?php echo $cardClass; ?>">
                        <div class="card-front" style="background-image: url('<?php echo $image; ?>');"></div>
                        <div class="card-back"></div>
                    </div>
                </button>
            </form>
        </div>
    <?php } ?>
</div>

<!-- Afficher le score actuel -->
<h3>Score : <?php echo $_SESSION['game']['score']; ?> points</h3>

<!-- Afficher le classement des 10 meilleurs scores -->
<h4>Top 10 des meilleurs scores :</h4>
<ul>
    <?php
    $stmt = $pdo->query("SELECT player_name, score FROM scores ORDER BY score DESC LIMIT 10");
    $topScores = $stmt->fetchAll();
    foreach ($topScores as $rank => $score) {
        echo "<li>" . ($rank + 1) . ". " . htmlspecialchars($score['player_name']) . " : " . $score['score'] . " points</li>";
    }
    ?>
</ul>

</body>
</html>
