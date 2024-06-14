<?php
require '../config/database.php'; // Make sure to include your database connection

$questions = []; // Initialize $questions as an empty array

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];
    $stmt = $pdo->prepare('SELECT * FROM questions WHERE quiz_id = ?');
    $stmt->execute([$quiz_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch results as an associative array
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
</head>
<body>
    <h1>Quiz</h1>
    <form method="post" action="submit_quiz.php">
        <?php foreach ($questions as $question): ?>
            <p><?php echo htmlspecialchars($question['text']); ?></p>
            <!-- Wyświetlanie odpowiedzi związanych z pytaniem -->
            <?php
            $stmt = $pdo->prepare('SELECT * FROM answers WHERE question_id = ?');
            $stmt->execute([$question['id']]);
            $answers = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch results as an associative array
            ?>
            <?php foreach ($answers as $answer): ?>
                <label>
                    <input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $answer['id']; ?>">
                    <?php echo htmlspecialchars($answer['text']); ?>
                </label><br>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
