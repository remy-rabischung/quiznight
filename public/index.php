<?php
$host = 'localhost';
$dbname = 'quiz_night';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion à la base de données réussie.";
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test de connexion à la base de données</title>
</head>
<body>
    <h1>Test de connexion à la base de données</h1>
    <?php
    try {
        $query = $pdo->query("SELECT * FROM quizzes");
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<pre>' . print_r($row, true) . '</pre>';
        }
    } catch(PDOException $e) {
        echo "Erreur de requête : " . $e->getMessage();
    }
    ?>
</body>
</html>
