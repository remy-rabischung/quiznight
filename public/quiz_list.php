<?php
include('../includes/header.php');
include('../config/database.php');

$sql = "SELECT * FROM quizzes";
$result = $conn->query($sql);
?>

<div class="container">
    <h1>Liste des Quiz</h1>
    <ul class="list-group">
        <?php while ($quiz = $result->fetch_assoc()): ?>
            <li class="list-group-item">
                <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
                <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                <a href="quiz_detail.php?id=<?php echo $quiz['id']; ?>" class="btn btn-primary">Jouer sur le site</a>
                <a href="quiz_detail.php?id=<?php echo $quiz['id']; ?>&mode=pub" class="btn btn-secondary">Mode Pub Quiz</a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php include('../includes/footer.php'); ?>
