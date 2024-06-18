<?php
require '../config/database.php';
require '../public/auth_check.php';
$stmt = $pdo->query('SELECT * FROM quizzes');
$quizzes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Quiz</title>
</head>
<body>
    <h1>Liste des Quiz</h1>
    <ul>
        <?php foreach ($quizzes as $quiz): ?>
            <li><?php echo htmlspecialchars($quiz['title']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
