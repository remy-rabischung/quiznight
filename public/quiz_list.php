<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../classes/Quiz.php';
require_once '../classes/Database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
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
