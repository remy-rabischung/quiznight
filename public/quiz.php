<?php
if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];
    $stmt = $pdo->prepare('SELECT * FROM questions WHERE quiz_id = ?');
    $stmt->execute([$quiz_id]);
    $questions = $stmt->fetchAll();
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
            <p><?php echo htmlspecialchars($question['content']); ?></p>
            <!-- Wyświetlanie odpowiedzi związanych z pytaniem -->
            <?php
            $stmt = $pdo->prepare('SELECT * FROM answers WHERE question_id = ?');
            $stmt->execute([$question['id']]);
            $answers = $stmt->fetchAll();
            ?>
            <?php foreach ($answers as $answer): ?>
                <label>
                    <input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $answer['id']; ?>">
                    <?php echo htmlspecialchars($answer['content']); ?>
                </label><br>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
