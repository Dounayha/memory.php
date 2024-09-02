<!-- game.php -->
<?php
// Récupération du nombre de paires depuis la requête GET
$pairs = isset($_GET['pairs']) ? (int)$_GET['pairs'] : 3; // Par défaut, 3 paires

// Liste des images disponibles pour le jeu (ajuster selon les images disponibles dans le dossier)
$images = [
    'aurore.png', 'blancheneige.png', 'jasmine.png', 
    'raiponce.png', 'reinedesneiges.png', 'sofia.png', 
    // Ajouter d'autres images si nécessaire
];

// Sélectionne un nombre d'images égal au nombre de paires et les duplique pour créer des paires
$selectedImages = array_slice($images, 0, $pairs);
$cards = array_merge($selectedImages, $selectedImages);

// Mélange les cartes
shuffle($cards);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game - Jouer</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Jeu de Memory</h1>
        <p>Trouvez toutes les paires pour gagner !</p>
    </header>

    <main class="game-board">
        <!-- Affichage des cartes -->
        <?php foreach ($cards as $index => $image): ?>
            <div class="card" data-id="<?= $index ?>">
                <div class="card-inner">
                    <div class="card-front">
                        <!-- Image de la carte -->
                        <img src="assets/images/<?= $image ?>" alt="Carte">
                    </div>
                    <div class="card-back">
                        <!-- Dos de la carte (peut être stylisé avec CSS) -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <footer>
        <a href="index.php">Revenir à l'accueil</a>
    </footer>

    <script>
        // JavaScript pour gérer le retournement des cartes et vérifier les paires
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', () => {
                card.querySelector('.card-inner').classList.toggle('flipped');
                // Ajoutez ici la logique pour vérifier les paires
            });
        });
    </script>
</body>
</html>
