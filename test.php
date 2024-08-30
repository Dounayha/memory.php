<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=memory_game', 'root', '');

// Variables pour les erreurs et messages
$message = "";
$player = null;

// Gestion de la connexion du joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_name'])) {
    $playerName = trim($_POST['player_name']);
    if (!empty($playerName)) {
        // Vérifie si le joueur existe déjà dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM players WHERE name = ?");
        $stmt->execute([$playerName]);
        $player = $stmt->fetch();

        // Si le joueur n'existe pas, on l'ajoute
        if (!$player) {
            $stmt = $pdo->prepare("INSERT INTO players (name) VALUES (?)");
            $stmt->execute([$playerName]);
            $player = ['id' => $pdo->lastInsertId(), 'name' => $playerName];
        }
    } else {
        $message = "Veuillez entrer un nom de joueur.";
    }
}

// Sauvegarder le score à la fin de la partie (par exemple, un score aléatoire ici pour la démonstration)
if ($player && isset($_POST['save_score'])) {
    $score = rand(50, 200); // Remplacez ceci par le score réel du joueur
    $stmt = $pdo->prepare("INSERT INTO scores (player_id, score) VALUES (?, ?)");
    $stmt->execute([$player['id'], $score]);
    $message = "Score enregistré : $score points";
}

// Récupérer les scores du joueur pour afficher son évolution
$playerScores = [];
if ($player) {
    $stmt = $pdo->prepare("SELECT score, created_at FROM scores WHERE player_id = ? ORDER BY created_at DESC");
    $stmt->execute([$player['id']]);
    $playerScores = $stmt->fetchAll();
}

// Récupérer les 10 meilleurs scores de tous les joueurs
$stmt = $pdo->query("SELECT p.name, s.score FROM scores s JOIN players p ON s.player_id = p.id ORDER BY s.score DESC LIMIT 10");
$topScores = $stmt->fetchAll();
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

<!-- Formulaire pour la connexion du joueur -->
<form method="post"action="index.php">
    <input type="text" name="player_name" placeholder="Entrez votre nom" required>
    <button type="submit">Jouer</button>
</form>

<!-- Affichage du message (erreurs ou confirmation) -->
<p><?php echo $message; ?></p>

<!-- Si le joueur est connecté, afficher ses scores et la possibilité de jouer -->
<?php if ($player): ?>
    <h3>Bienvenue, <?php echo htmlspecialchars($player['name']); ?> !</h3>

    <!-- Simuler la fin de la partie et enregistrer un score -->
    <form method="post">
        <input type="hidden" name="save_score" value="1">
        <button type="submit">Enregistrer un score aléatoire</button>
    </form>

    <!-- Afficher les anciens scores pour voir l'évolution -->
    <h4>Vos scores :</h4>
    <ul>
        <?php foreach ($playerScores as $score): ?>
            <li><?php echo $score['score']; ?> points - <?php echo $score['created_at']; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- Afficher le classement des 10 meilleurs joueurs -->
<h4>Top 10 des meilleurs scores :</h4>
<ul>
    <?php foreach ($topScores as $rank => $score): ?>
        <li><?php echo ($rank + 1) . '. ' . htmlspecialchars($score['name']) . ' : ' . $score['score'] . ' points'; ?></li>
    <?php endforeach; ?>
</ul>

</body>
</html>
