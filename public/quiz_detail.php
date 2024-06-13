<?php
include('../includes/header.php');
include('../config/database.php');

if (!isset($_GET['id'])) {
    die("ID de quiz manquant.");
}

$quizId = $_GET['id'];
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'site';

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

$currentQuestionIndex = isset($_GET['question']) ? (int)$_GET['question'] : 0;

if ($currentQuestionIndex < 0) {
    $currentQuestionIndex = 0;
} elseif ($currentQuestionIndex >= count($questions)) {
    $currentQuestionIndex = count($questions) - 1;
}

$currentQuestion = $questions[$currentQuestionIndex];

$sql = "SELECT * FROM answers WHERE question_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $currentQuestion['id']);
$stmt->execute();
$result = $stmt->get_result();
$answers = [];
while ($row = $result->fetch_assoc()) {
    $answers[] = $row;
}

$showAnswers = isset($_GET['show_answers']) && $_GET['show_answers'] == '1';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $mode === 'site') {
    session_start();
    if (!isset($_SESSION['score'])) {
        $_SESSION['score'] = 0;
    }

    $selectedAnswerId = $_POST['answer'];
    $sql = "SELECT is_correct FROM answers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selectedAnswerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $selectedAnswer = $result->fetch_assoc();

    if ($selectedAnswer['is_correct']) {
        $_SESSION['score']++;
    }

    if ($currentQuestionIndex < count($questions) - 1) {
        header("Location: quiz_detail.php?id=$quizId&mode=$mode&question=" . ($currentQuestionIndex + 1));
        exit();
    } else {
        $finalScore = $_SESSION['score'];
        session_destroy();
    }
}
?>

<div class="container">
    <h2><?php echo htmlspecialchars($currentQuestion['question_text']); ?></h2>
    <?php if ($mode === 'site'): ?>
        <?php if (isset($finalScore)): ?>
            <div class="alert alert-success" role="alert">
                Votre score final est: <?php echo $finalScore; ?> / <?php echo count($questions); ?>
            </div>
        <?php else: ?>
            <form method="post" action="quiz_detail.php?id=<?php echo $quizId; ?>&mode=<?php echo $mode; ?>&question=<?php echo $currentQuestionIndex; ?>">
                <?php foreach ($answers as $answer): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer" id="answer<?php echo $answer['id']; ?>" value="<?php echo $answer['id']; ?>">
                        <label class="form-check-label" for="answer<?php echo $answer['id']; ?>">
                            <?php echo htmlspecialchars($answer['answer_text']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <div class="mt-3">
                    <a href="quiz_detail.php?id=<?php echo $quizId; ?>&mode=<?php echo $mode; ?>&question=<?php echo $currentQuestionIndex - 1; ?>" class="btn btn-secondary">Précédent</a>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                    <a href="quiz_detail.php?id=<?php echo $quizId; ?>&mode=<?php echo $mode; ?>&question=<?php echo $currentQuestionIndex + 1; ?>" class="btn btn-primary">Suivant</a>
                </div>
            </form>
        <?php endif; ?>
    <?php elseif ($mode === 'pub'): ?>
        <ul class="list-group">
            <?php foreach ($answers as $answer): ?>
                <li class="list-group-item"><?php echo htmlspecialchars($answer['answer_text']); ?></li>
            <?php endforeach; ?>
        </ul>
        <div class="mt-3">
            <a href="quiz_detail.php?id=<?php echo $quizId; ?>&mode=<?php echo $mode; ?>&question=<?php echo $currentQuestionIndex - 1; ?>" class="btn btn-secondary">Précédent</a>
            <?php if ($currentQuestionIndex < count($questions) - 1): ?>
                <a href="quiz_detail.php?id=<?php echo $quizId; ?>&mode=<?php echo $mode; ?>&question=<?php echo $currentQuestionIndex + 1; ?>" class="btn btn-primary">Suivant</a>
            <?php else: ?>
                <a href="quiz_detail.php?id=<?php echo $quizId; ?>&mode=pub&show_answers=1" class="btn btn-primary">Voir les réponses</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($showAnswers): ?>
    <div class="container mt-5">
        <h2>Réponses Correctes</h2>
        <ul class="list-group">
            <?php foreach ($questions as $question): ?>
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
                            if ($answer['is_correct']) {
                                echo "<li><strong>" . htmlspecialchars($answer['answer_text']) . "</strong> (Correct)</li>";
                            }
                        }
                        ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>
