<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Bienvenue dans le jeu Memory</h1>
    </header>

    <main>
        <section class="game-setup">
            <h2>Configurer votre partie</h2>
            <form action="game.php" method="GET">
                <label for="pairs">Choisissez le nombre de paires :</label>
                <select name="pairs" id="pairs" required>
                    <option value="3">3 paires (6 cartes)</option>
                    <option value="4">4 paires (8 cartes)</option>
                    <option value="5">5 paires (10 cartes)</option>
                    <option value="6">6 paires (12 cartes)</option>
                    <option value="7">7 paires (14 cartes)</option>
                    <option value="8">8 paires (16 cartes)</option>
                    <option value="9">9 paires (18 cartes)</option>
                    <option value="10">10 paires (20 cartes)</option>
                    <option value="11">11 paires (22 cartes)</option>
                    <option value="12">12 paires (24 cartes)</option>
                </select>
                <button type="submit">Démarrer la partie</button>
            </form>
        </section>

        <section class="leaderboard">
            <h2>Classement des Meilleurs Joueurs</h2>
            <a href="leaderboard.php">Voir le Classement</a>
        </section>

        <section class="profile">
            <h2>Votre Profil</h2>
            <a href="profile.php">Voir votre progression</a>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Memory Game. Tous droits réservés.</p>
    </footer>
</body>
</html>
