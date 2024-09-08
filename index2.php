<?php
session_start();

// Configuration de la connexion à la base de données
$host = 'localhost';
$db = 'memory_game';
$user = 'root';
$pass = '';

// Connexion à la base de données
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Vérification si un nombre de paires a été sélectionné
$pair_count = isset($_GET['pair_count']) ? (int)$_GET['pair_count'] : 3; // par défaut 3 paires

// Récupération des meilleurs scores (Top 10) - Basé sur la moyenne des scores, tri croissant, filtré par le nombre de paires
$topPlayersQuery = "SELECT u.username, AVG(s.score) AS average_score
                    FROM users u
                    JOIN scores s ON u.id = s.user_id
                    WHERE s.pair_count = ?
                    GROUP BY u.username
                    ORDER BY average_score ASC
                    LIMIT 10";
$stmtTopPlayers = $mysqli->prepare($topPlayersQuery);
$stmtTopPlayers->bind_param("i", $pair_count);
$stmtTopPlayers->execute();
$topPlayersResult = $stmtTopPlayers->get_result();

// Récupération des scores de l'utilisateur connecté
$userScoresQuery = "SELECT score, date_played, pair_count
                    FROM scores
                    WHERE user_id = ?
                    ORDER BY date_played DESC";
$stmtUserScores = $mysqli->prepare($userScoresQuery);
$stmtUserScores->bind_param("i", $user_id);
$stmtUserScores->execute();
$userScoresResult = $stmtUserScores->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Memory Game - Classement</title>
    <style>
        /* Styles simples pour une meilleure présentation */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .btn {
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h1>Bienvenue, <?= htmlspecialchars($username) ?> !</h1>

    <!-- Bouton pour accéder au jeu -->
    <a href="logout.php" class="btn">Déconnexion</a>

    <!-- Formulaire pour démarrer une nouvelle partie avec choix des paires -->
    <h2>Commencer une nouvelle partie</h2>
    <form method="POST" action="interface.php">
        <label for="pair_count">Choisissez le nombre de paires (3 à 12) :</label>
        <input type="number" id="pair_count" name="pair_count" min="3" max="12" required>
        <button type="submit">Démarrer le jeu</button>
    </form>

    <!-- Formulaire de sélection du nombre de paires pour le classement -->
    <h2>Classement des 10 meilleurs joueurs</h2>
    <form method="GET" action="">
        <label for="pair_count">Filtrer par nombre de paires :</label>
        <input type="number" id="pair_count" name="pair_count" min="3" max="12" value="<?= htmlspecialchars($pair_count) ?>">
        <button type="submit">Filtrer</button>
    </form>

    <!-- Affichage du classement des meilleurs joueurs pour le nombre de paires sélectionné -->
    <table>
        <thead>
            <tr>
                <th>Position</th>
                <th>Nom d'utilisateur</th>
                <th>Moyenne des Scores</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($topPlayersResult->num_rows > 0) {
                $position = 1;
                while ($row = $topPlayersResult->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $position++ . "</td>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars(number_format($row['average_score'], 2)) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Aucun joueur classé pour le moment.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Profil individuel du joueur -->
    <h2>Vos Scores</h2>
    <?php
    if ($userScoresResult->num_rows > 0) {
        while ($row = $userScoresResult->fetch_assoc()) {
            echo "Score: " . htmlspecialchars($row['score']) . " - Date: " . htmlspecialchars($row['date_played']) . " - Paires: " . htmlspecialchars($row['pair_count']) . "<br>";
        }
    } else {
        echo "Aucun score trouvé.";
    }

    $stmtTopPlayers->close();
    $stmtUserScores->close();
    $mysqli->close();
    ?>
</body>
</html>