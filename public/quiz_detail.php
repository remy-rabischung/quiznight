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

$db = new Database();
$quiz = new Quiz($db);

// Vérifiez que l'ID du quiz est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de quiz non spécifié.");
}

$quiz_id = $_GET['id'];
$quiz_data = $quiz->getQuizById($quiz_id);
$questions = $quiz->getQuestionsByQuizId($quiz_id);

// Initialiser la session pour suivre la question actuelle
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 0;
    $_SESSION['answers'] = [];
}

// Gestion de la navigation et soumission des réponses
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['next'])) {
        if (isset($_POST['answer'])) {
            $_SESSION['answers'][$_SESSION['current_question']] = $_POST['answer'];
        }
        $_SESSION['current_question']++;
    } elseif (isset($_POST['prev'])) {
        $_SESSION['current_question']--;
    } elseif (isset($_POST['submit'])) {
        if (isset($_POST['answer'])) {
            $_SESSION['answers'][$_SESSION['current_question']] = $_POST['answer'];
        }
        $score = 0;
        foreach ($questions as $index => $question) {
            $question_id = $question['id'];
            if (isset($_SESSION['answers'][$index])) {
                $answer_id = $_SESSION['answers'][$index];
                $answers = $quiz->getAnswersByQuestionId($question_id);
                foreach ($answers as $answer) {
                    if ($answer['id'] == $answer_id && $answer['is_correct']) {
                        $score++;
                    }
                }
            }
        }
        echo "Votre score : $score / " . count($questions);
        session_destroy();
        exit();
    }
}

$current_question_index = $_SESSION['current_question'];
if ($current_question_index < 0) {
    $current_question_index = 0;
    $_SESSION['current_question'] = 0;
} elseif ($current_question_index >= count($questions)) {
    $current_question_index = count($questions) - 1;
    $_SESSION['current_question'] = $current_question_index;
}

$current_question = $questions[$current_question_index];
$answers = $quiz->getAnswersByQuestionId($current_question['id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($quiz_data['title']); ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
    <h1><?php echo htmlspecialchars($quiz_data['title']); ?></h1>
    <p><?php echo htmlspecialchars($quiz_data['description']); ?></p>
    <form method="POST" action="quiz_detail.php?id=<?php echo $quiz_id; ?>">
        <h3><?php echo htmlspecialchars($current_question['question_text']); ?></h3>
        <?php foreach ($answers as $answer): ?>
            <label>
                <input type="radio" name="answer" value="<?php echo $answer['id']; ?>" <?php echo isset($_SESSION['answers'][$current_question_index]) && $_SESSION['answers'][$current_question_index] == $answer['id'] ? 'checked' : ''; ?>>
                <?php echo htmlspecialchars($answer['answer_text']); ?>
            </label><br>
        <?php endforeach; ?>
        <?php if ($current_question_index > 0): ?>
            <button type="submit" name="prev" class="btn btn-secondary">Précédent</button>
        <?php endif; ?>
        <?php if ($current_question_index < count($questions) - 1): ?>
            <button type="submit" name="next" class="btn btn-primary">Suivant</button>
        <?php else: ?>
            <button type="submit" name="submit" class="btn btn-success">Soumettre le quiz</button>
        <?php endif; ?>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
