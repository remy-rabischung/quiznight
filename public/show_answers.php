<?php
include('../config/database.php');
include('../includes/header.php'); // Inclure l'en-tête

if (!isset($_GET['id'])) {
    die("ID de quiz manquant.");
}

$quizId = $_GET['id'];

function getCorrectAnswersByQuizId($quizId) {
    global $conn;
    $sql = "SELECT q.question_text, a.answer_text 
            FROM questions q 
            JOIN answers a ON q.id = a.question_id 
            WHERE q.quiz_id = ? AND a.is_correct = 1";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $quizId);
        $stmt->execute();
        $result = $stmt->get_result();
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[$row['question_text']] = $row['answer_text'];
        }
        return $questions;
    } else {
        echo "Erreur de préparation de la requête: " . $conn->error;
        return [];
    }
}

$questions = getCorrectAnswersByQuizId($quizId);
?>

<div class="container">
    <h2>Réponses correctes</h2>
    <ul>
        <?php foreach ($questions as $question_text => $answer_text): ?>
            <li>
                <strong><?php echo htmlspecialchars($question_text); ?></strong>: <?php echo htmlspecialchars($answer_text); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="quiz_detail.php?id=<?php echo $quizId; ?>" class="btn btn-secondary">Retour au Quiz</a>
</div>

<?php include('../includes/footer.php'); ?>
