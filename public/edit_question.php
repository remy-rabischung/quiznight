<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../classes/Quiz.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("Accès refusé.");
}

$db = new Database();
$quiz = new Quiz($db);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de question non spécifié.");
}

$question_id = $_GET['id'];
$question_data = $quiz->getQuestionById($question_id);
$answers = $quiz->getAnswersByQuestionId($question_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_text = $_POST['question_text'];
    $quiz->updateQuestion($question_id, $question_text);
    foreach ($answers as $answer) {
        $answer_id = $answer['id'];
        $answer_text = $_POST['answer_' . $answer_id];
        $is_correct = isset($_POST['correct_' . $answer_id]) ? 1 : 0;
        $quiz->updateAnswer($answer_id, $answer_text, $is_correct);
    }
    header("Location: quiz_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Question</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
    <h1>Modifier Question</h1>
    <form method="POST" action="edit_question.php?id=<?php echo $question_id; ?>">
        <div class="mb-3">
            <label for="question_text" class="form-label">Question</label>
            <input type="text" class="form-control" id="question_text" name="question_text" value="<?php echo htmlspecialchars($question_data['question_text']); ?>" required>
        </div>
        <?php foreach ($answers as $answer): ?>
            <div class="mb-3">
                <label for="answer_<?php echo $answer['id']; ?>" class="form-label">Réponse</label>
                <input type="text" class="form-control" id="answer_<?php echo $answer['id']; ?>" name="answer_<?php echo $answer['id']; ?>" value="<?php echo htmlspecialchars($answer['answer_text']); ?>" required>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="correct_<?php echo $answer['id']; ?>" name="correct_<?php echo $answer['id']; ?>" <?php echo $answer['is_correct'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="correct_<?php echo $answer['id']; ?>">Correct</label>
                </div>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
