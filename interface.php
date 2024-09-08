<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'memory_game';
$username = 'root';  
$password = '';      

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Veuillez vous connecter pour jouer.");
}

// Initialisation du jeu ou récupération des données existantes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pair_count']) && !isset($_SESSION['cards'])) {
    $_SESSION['pair_count'] = intval($_POST['pair_count']);
    $pairCount = $_SESSION['pair_count'];

    // Créer les paires de cartes avec les chemins des images
    $images = ['./assets/images/aurore.png', './assets/images/blancheneige.png', './assets/images/jasmine.png']; // Remplacez par vos images
    $cards = array_merge($images, $images);
    shuffle($cards);

    $_SESSION['cards'] = $cards;
    $_SESSION['flipped'] = array_fill(0, count($cards), false);
    $_SESSION['attempts'] = 0;
    $_SESSION['matched_pairs'] = 0;
    $_SESSION['first_card'] = null;
    $_SESSION['second_card'] = null;
    $_SESSION['wait_for_flip'] = false;
} else {
    $pairCount = isset($_SESSION['pair_count']) ? $_SESSION['pair_count'] : 3;
}

// Gestion du clic sur une carte
if (isset($_POST['card_index'])) {
    $cardIndex = intval($_POST['card_index']);

    // Vérifier si la carte n'est pas déjà retournée et que nous ne sommes pas en attente
    if (!$_SESSION['flipped'][$cardIndex] && !$_SESSION['wait_for_flip']) {
        if ($_SESSION['first_card'] === null) {
            $_SESSION['first_card'] = $cardIndex;
            $_SESSION['flipped'][$cardIndex] = true;
        } elseif ($_SESSION['second_card'] === null) {
            $_SESSION['second_card'] = $cardIndex;
            $_SESSION['flipped'][$cardIndex] = true;
            $_SESSION['attempts']++;

            $firstIndex = $_SESSION['first_card'];
            $secondIndex = $_SESSION['second_card'];

            // Vérifier si les deux cartes correspondent
            if ($_SESSION['cards'][$firstIndex] === $_SESSION['cards'][$secondIndex]) {
                $_SESSION['matched_pairs']++;
                $_SESSION['first_card'] = null;
                $_SESSION['second_card'] = null;
            } else {
                // Les cartes ne correspondent pas, attendre pour les retourner
                $_SESSION['wait_for_flip'] = true;
                echo '<script>
                    setTimeout(function() {
                        document.getElementById("reset-form").submit();
                    }, 1000);
                </script>';
            }
        }
    }
}

// Réinitialiser l'état après un retournement incorrect
if ($_SESSION['wait_for_flip'] && isset($_SESSION['first_card']) && isset($_SESSION['second_card'])) {
    $firstIndex = $_SESSION['first_card'];
    $secondIndex = $_SESSION['second_card'];

    $_SESSION['flipped'][$firstIndex] = false;
    $_SESSION['flipped'][$secondIndex] = false;
    $_SESSION['first_card'] = null;
    $_SESSION['second_card'] = null;
    $_SESSION['wait_for_flip'] = false;
}

// Vérifier si le jeu est terminé
if ($_SESSION['matched_pairs'] == $pairCount) {
    echo "<h2>Félicitations ! Vous avez terminé le jeu en " . $_SESSION['attempts'] . " essais.</h2>";

    // Insérer le score dans la base de données avec le nombre de paires
    $userId = $_SESSION['user_id'];
    $score = $_SESSION['attempts'];
    $pairCount = $_SESSION['pair_count']; // Récupération du nombre de paires

    try {
        $stmt = $pdo->prepare("INSERT INTO scores (user_id, score, date_played, pair_count) VALUES (:user_id, :score, NOW(), :pair_count)");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':score', $score, PDO::PARAM_INT);
        $stmt->bindParam(':pair_count', $pairCount, PDO::PARAM_INT); // Liaison du nombre de paires
        $stmt->execute();

        echo "<p>Score enregistré avec succès !</p>";
    } catch (PDOException $e) {
        echo "<p>Erreur lors de l'enregistrement du score : " . $e->getMessage() . "</p>";
    }

    // Réinitialiser les variables du jeu
    unset($_SESSION['cards'], $_SESSION['flipped'], $_SESSION['first_card'], $_SESSION['second_card'], $_SESSION['wait_for_flip']);

    echo '<a href="index.php" class="btn">Revenir au jeu</a>';
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
            cursor: pointer;
            border: 1px solid #000;
            transition: background-color 0.5s;
        }
        .flipped {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Jeu de Memory</h1>
        <h3>Paires trouvées : <?= $_SESSION['matched_pairs'] ?>/<?= $pairCount ?></h3>
        <h3>Nombre d'essais : <?= $_SESSION['attempts'] ?></h3>

        <div class="game-board">
            <?php foreach ($_SESSION['cards'] as $index => $cardImage): ?>
                <form method="post" style="display:inline-block;">
                    <button type="submit" name="card_index" value="<?= $index ?>" class="card <?= $_SESSION['flipped'][$index] ? 'flipped' : '' ?>">
                        <?php if ($_SESSION['flipped'][$index]): ?>
                            <img src="./assets/images/aurore.png/<?= $cardImage ?>" alt="Card Image" style="width:100%; height:100%;">
                        <?php else: ?>
                            <img src="images/back.png" alt="Card Back" style="width:100%; height:100%;">
                        <?php endif; ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>

        <!-- Formulaire invisible pour réinitialiser l'état des cartes après un délai -->
        <form id="reset-form" method="post" style="display:none;"></form>
    </div>
</body>
</html>
