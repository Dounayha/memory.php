<?php
class Leaderboard {
    private $mysqli;

    // Constructeur qui initialise la connexion à la base de données
    public function __construct($host, $user, $pass, $db) {
        $this->mysqli = new mysqli($host, $user, $pass, $db);
        if ($this->mysqli->connect_error) {
            die("Erreur de connexion : " . $this->mysqli->connect_error);
        }
    }

    // Méthode pour mettre à jour le classement en ajoutant un score de joueur
    public function updateLeaderboard($userId, $score) {
        $stmt = $this->mysqli->prepare("INSERT INTO scores (user_id, score, date_played) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $score);
        $stmt->execute();
        $stmt->close();
    }

    // Méthode pour récupérer les 10 joueurs avec les scores les plus bas depuis la base de données
    public function getTopPlayers() {
        $query = "SELECT u.username, MIN(s.score) AS worst_score
                  FROM users u
                  JOIN scores s ON u.id = s.user_id
                  GROUP BY u.username
                  ORDER BY worst_score ASC
                  LIMIT 10";

        if ($result = $this->mysqli->query($query)) {
            $topPlayers = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $topPlayers[] = [
                        'username' => $row['username'],
                        'score' => $row['worst_score']
                    ];
                }
            }

            $result->free();
            return $topPlayers;
        } else {
            // Afficher les erreurs de la requête SQL
            die("Erreur de requête : " . $this->mysqli->error);
        }
    }

    // Méthode de destruction pour fermer la connexion
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>