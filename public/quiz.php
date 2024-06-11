<?php
session_start();
include '../config/database.php';
include '../includes/header.php'; // Inclure le fichier d'en-tête


// Vérifiez que l'ID du quiz est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de quiz non spécifié.");
}

$quiz_id = $_GET['id'];

// Récupérer les informations du quiz
$sql_quiz = "SELECT * FROM quizzes WHERE id = ?";
$stmt_quiz = $conn->prepare($sql_quiz);
$stmt_quiz->bind_param("i", $quiz_id);
$stmt_quiz->execute();
$result_quiz = $stmt_quiz->get_result();

if ($result_quiz->num_rows == 0) {
    die("Quiz non trouvé.");
}

$quiz = $result_quiz->fetch_assoc();

// Récupérer les questions du quiz
$sql_questions = "SELECT * FROM questions WHERE quiz_id = ? ORDER BY id";
$stmt_questions = $conn->prepare($sql_questions);
$stmt_questions->bind_param("i", $quiz_id);
$stmt_questions->execute();
$result_questions = $stmt_questions->get_result();
$questions = [];
while ($row = $result_questions->fetch_assoc()) {
    $questions[] = $row;
}

// Initialiser la session pour suivre la question actuelle
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 0;
}

// Gestion de la navigation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['next'])) {
        // Aller à la question suivante
        $_SESSION['current_question']++;
    } elseif (isset($_POST['prev'])) {
        // Aller à la question précédente
        $_SESSION['current_question']--;
    } elseif (isset($_POST['submit'])) {
        // Soumettre le quiz et calculer le score
        $score = 0;
        $answers_feedback = [];
        foreach ($questions as $question) {
            $question_id = $question['id'];
            if (isset($_POST['question_' . $question_id])) {
                $answer_id = $_POST['question_' . $question_id];
                $sql_answer = "SELECT is_correct FROM answers WHERE id = ?";
                $stmt_answer = $conn->prepare($sql_answer);
                $stmt_answer->bind_param("i", $answer_id);
                $stmt_answer->execute();
                $result_answer = $stmt_answer->get_result();
                $answer = $result_answer->fetch_assoc();
                if ($answer['is_correct']) {
                    $score++;
                    $answers_feedback[$question_id] = "Correct !";
                } else {
                    $answers_feedback[$question_id] = "Faux.";
                }
            } else {
                $answers_feedback[$question_id] = "Non répondu.";
            }
        }
        echo "Votre score : $score / " . count($questions) . "<br>";
        foreach ($answers_feedback as $question_id => $feedback) {
            echo "Question #" . $question_id . ": " . $feedback . "<br>";
        }
        session_destroy();
        exit();
    }
}

// Assurez-vous que la question actuelle est dans les limites valides
$current_question_index = $_SESSION['current_question'];
if ($current_question_index < 0) {
    $current_question_index = 0;
    $_SESSION['current_question'] = 0;
} elseif ($current_question_index >= count($questions)) {
    $current_question_index = count($questions) - 1;
    $_SESSION['current_question'] = $current_question_index;
}

// Récupérer les réponses de la question actuelle
$current_question = $questions[$current_question_index];
$sql_answers = "SELECT * FROM answers WHERE question_id = ?";
$stmt_answers = $conn->prepare($sql_answers);
$stmt_answers->bind_param("i", $current_question['id']);
$stmt_answers->execute();
$result_answers = $stmt_answers->get_result();
$answers = [];
while ($row = $result_answers->fetch_assoc()) {
    $answers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($quiz['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
    <p><?php echo htmlspecialchars($quiz['description']); ?></p>

    <?php
    // Afficher le message d'erreur si aucune réponse n'est sélectionnée
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_answer']) && !isset($_POST['question_' . $current_question['id']])) {
        echo "<p style='color: red;'>Veuillez sélectionner une réponse avant de soumettre.</p>";
    }
    ?>

    <form method="POST" action="quiz.php?id=<?php echo $quiz_id; ?>">
        <h3><?php echo htmlspecialchars($current_question['question_text']); ?></h3>
        <?php
        foreach ($answers as $answer) {
            echo "<label>";
            echo "<input type='radio' name='question_" . $current_question['id'] . "' value='" . $answer['id'] . "'>";
            echo htmlspecialchars($answer['answer_text']);
            echo "</label><br>";
        }
        ?>
        <?php if ($current_question_index > 0): ?>
            <button type="submit" name="prev">Précédent</button>
        <?php endif; ?>
        <button type="submit" name="submit_answer">Soumettre la réponse</button>
        <?php if ($current_question_index < count($questions) - 1): ?>
            <button type="submit" name="next">Suivant</button>
        <?php else: ?>
            <button type="submit" name="submit">Soumettre le quiz</button>
        <?php endif; ?>
    </form>

    <?php
    // Afficher le message de feedback après la soumission de la réponse
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_answer']) && isset($_POST['question_' . $current_question['id']])) {
        $answer_id = $_POST['question_' . $current_question['id']];
        $sql_answer = "SELECT is_correct FROM answers WHERE id = ?";
        $stmt_answer = $conn->prepare($sql_answer);
        $stmt_answer->bind_param("i", $answer_id);
        $stmt_answer->execute();
        $result_answer = $stmt_answer->get_result();
        $answer = $result_answer->fetch_assoc();
        if ($answer['is_correct']) {
            echo "<p style='color: green;'>Correct !</p>";
        } else {
            echo "<p style='color: red;'>Faux.</p>";
        }
    }
    ?>
</body>
</html>
<?php include '../includes/footer.php'; // Inclure le fichier de pied de page ?>
<?php
$conn->close();
?>
