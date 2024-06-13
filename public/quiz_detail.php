<?php
include('../includes/header.php');
include('../config/database.php');

if (!isset($_GET['id'])) {
    die("ID de quiz manquant.");
}

$quizId = $_GET['id'];

function getQuestionsByQuizId($quizId) {
    global $conn;
    $sql = "SELECT * FROM questions WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $quizId);
        $stmt->execute();
        $result = $stmt->get_result();
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
        return $questions;
    } else {
        echo "Erreur de préparation de la requête: " . $conn->error;
        return [];
    }
}

$questions = getQuestionsByQuizId($quizId);
?>

<div class="container">
    <h2>Mode Pub Quiz</h2>
    <ul class="list-group">
        <?php foreach ($questions as $index => $question): ?>
            <li class="list-group-item">
                <strong><?php echo htmlspecialchars($question['question_text']); ?></strong>
                <ul>
                    <?php
                    $sql = "SELECT * FROM answers WHERE question_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $question['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($answer = $result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($answer['answer_text']) . "</li>";
                    }
                    ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="show_answers.php?id=<?php echo $quizId; ?>" class="btn btn-primary mt-3">Voir les réponses</a>
    <a href="../includes/generate_pdf.php?id=<?php echo $quizId; ?>" class="btn btn-secondary mt-3">Télécharger le PDF</a>
</div>

<?php include('../includes/footer.php'); ?>
