<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <link rel="stylesheet" href="./assets/images/css/style.css?t=<?php echo time(); ?>"> 
</head>
<body>
    <section class="container">
        <h2>Memory Game</h2>
        <div class="game">
            <?php
            // Tableau des chemins d'images
            $images = [
                './assets/images/aurore.png',
                './assets/images/blancheneige.png',
                './assets/images/jasmine.png',
                './assets/images/raiponce.png',
                './assets/images/sofia.png',
                './assets/images/reinedesneiges.png'
            ];

            // Duplique chaque image pour créer des paires
            $cardsArray = array_merge($images, $images);

            // Mélange les cartes
            shuffle($cardsArray);

            // Génère les cartes en boucle
            foreach ($cardsArray as $image) {
                echo '
                <div class="card">
                    <div class="card-inner">
                        <div class="card-front"></div>
                        <div class="card-back">
                            <img src="' . $image . '" alt="carte">
                        </div>
                    </div>
                </div>
                ';
            }
            ?>
        </div>
        <button class="reset">Reset Game</button>
    </section>
</body>
</html>
