<?php
include '../config/database.php';

// Récupérer tous les quiz
$sql = "SELECT * FROM quizzes";
$result = $conn->query($sql);
include '../includes/header.php'; // Inclure le fichier d'en-tête
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Les Quiz du moment</h1>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='quiz.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></li>";
            }
        } else {
            echo "<li>Aucun quiz disponible.</li>";
        }
        ?>
    </ul>
</body>
</html>

<?php include '../includes/footer.php'; // Inclure le fichier de pied de page ?>

<?php
$conn->close();
?>
