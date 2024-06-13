<?php
include('../includes/header.php');
include('../config/database.php');

// Vérifiez si l'ID du quiz et le mode sont passés dans l'URL
if (!isset($_GET['id']) || !isset($_GET['mode'])) {
    echo "ID du quiz ou mode manquant.";
    exit();
}

$quizId = $_GET['id'];
$mode = $_GET['mode'];

function getQuestionsByQuizId($quizId) {
    global $conn;
    $sql = "SELECT q.id as question_id, q.question_text, a.id as answer_id, a.answer_text, a.is_correct
            FROM questions q 
            LEFT JOIN answers a ON q.id = a.question_id 
            WHERE q.quiz_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $quizId);
        $stmt->execute();
        $result = $stmt->get_result();
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $question_id = $row['question_id'];
            if (!isset($questions[$question_id])) {
                $questions[$question_id] = [
                    'question_text' => $row['question_text'],
                    'answers' => []
                ];
            }
            $questions[$question_id]['answers'][] = [
                'answer_id' => $row['answer_id'],
                'answer_text' => $row['answer_text'],
                'is_correct' => $row['is_correct']
            ];
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
    <div id="quiz-container">
        <?php if (count($questions) > 0): ?>
            <?php $index = 0; foreach ($questions as $question_id => $question): ?>
                <div class="question" id="question-<?php echo $index; ?>" <?php echo $index !== 0 ? 'style="display:none;"' : ''; ?>>
                    <h3><?php echo htmlspecialchars($question['question_text']); ?></h3>
                    <ul>
                        <?php foreach ($question['answers'] as $answer): ?>
                            <?php if ($mode === 'online'): ?>
                                <li>
                                    <label>
                                        <input type="radio" name="question_<?php echo $question_id; ?>" value="<?php echo $answer['answer_id']; ?>" data-correct="<?php echo $answer['is_correct']; ?>">
                                        <?php echo htmlspecialchars($answer['answer_text']); ?>
                                    </label>
                                </li>
                            <?php else: ?>
                                <li><?php echo htmlspecialchars($answer['answer_text']); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php $index++; endforeach; ?>
            <button id="prev-button" class="btn btn-secondary" style="display:none;">Précédent</button>
            <button id="next-button" class="btn btn-primary">Suivant</button>
            <?php if ($mode === 'online'): ?>
                <button id="submit-button" class="btn btn-success" style="display:none;">Soumettre</button>
            <?php endif; ?>
            <?php if ($mode === 'pub'): ?>
                <a href="../includes/generate_pdf.php?id=<?php echo $quizId; ?>" class="btn btn-secondary">Télécharger en PDF</a>
            <?php endif; ?>
        <?php else: ?>
            <p>Aucune question trouvée pour ce quiz.</p>
        <?php endif; ?>
    </div>
</div>

<script>
let currentQuestionIndex = 0;
const questions = document.querySelectorAll('.question');
const nextButton = document.getElementById('next-button');
const prevButton = document.getElementById('prev-button');
const submitButton = document.getElementById('submit-button');

nextButton.addEventListener('click', () => {
    questions[currentQuestionIndex].style.display = 'none';
    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        questions[currentQuestionIndex].style.display = 'block';
    } 
    if (currentQuestionIndex === questions.length - 1) {
        nextButton.style.display = 'none';
        if (submitButton) submitButton.style.display = 'block';
    }
    if (currentQuestionIndex > 0) {
        prevButton.style.display = 'block';
    }
});

prevButton.addEventListener('click', () => {
    if (currentQuestionIndex > 0) {
        questions[currentQuestionIndex].style.display = 'none';
        currentQuestionIndex--;
        questions[currentQuestionIndex].style.display = 'block';
        if (currentQuestionIndex === 0) {
            prevButton.style.display = 'none';
        }
        if (currentQuestionIndex < questions.length - 1) {
            nextButton.style.display = 'block';
            if (submitButton) submitButton.style.display = 'none';
        }
    }
});

if (submitButton) {
    submitButton.addEventListener('click', () => {
        let score = 0;
        questions.forEach((question, index) => {
            const selectedAnswer = question.querySelector('input[type="radio"]:checked');
            if (selectedAnswer) {
                const isCorrect = selectedAnswer.getAttribute('data-correct') === '1';
                if (isCorrect) score++;
            }
        });
        alert(`Votre score : ${score} / ${questions.length}`);
    });
}
</script>

<?php include('../includes/footer.php'); ?>
