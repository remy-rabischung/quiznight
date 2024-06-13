<?php
include('../includes/header.php');
include('../config/database.php');

// Récupérer tous les quiz
$sql = "SELECT * FROM quizzes";
$result = $conn->query($sql);
?>

<div class="container">
    <h1>Liste des Quiz</h1>
    <ul>
        <?php while ($quiz = $result->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($quiz['title']); ?>
                <a href="quiz.php?id=<?php echo $quiz['id']; ?>&mode=online" class="btn btn-primary">Jouer en ligne</a>
                <a href="quiz.php?id=<?php echo $quiz['id']; ?>&mode=pub" class="btn btn-secondary">Mode Pub Quiz</a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php
include('../includes/footer.php');
?>
