<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <link rel="stylesheet" href="./assets/images/css/style.css">
</head>
<body>

<h2>Jeu de Memory</h2>
<div class="card-container">
    <?php
    // Images des cartes (utilisez le même chemin pour chaque paire)
    $images = [
        './assets/images/aurore.png',
        './assets/images/blancheneige.png',
        './assets/images/raiponce.png'
    ];

    // Double les images pour créer des paires
    $cards = array_merge($images, $images);

    // Mélange les cartes
    shuffle($cards);

    // Génère les cartes
    foreach ($cards as $index => $image) {
        echo '<div class="card">';
        echo '<input type="checkbox" id="card' . $index . '" class="card-checkbox">';
        echo '<label for="card' . $index . '">';
        echo '<div class="card-inner">';
        echo '<div class="card-front" style="background-image: url(' . $image . ');"></div>';
        echo '<div class="card-back"></div>';
        echo '</div>';
        echo '</label>';
        echo '</div>';
    }
    ?>
</div>

</body>
</html>
