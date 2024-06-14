<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../config/database.php';
require '../classes/Quiz.php';
require '../classes/Question.php';
require '../classes/Answer.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$quiz = new Quiz($pdo);
$question = new Question($pdo);
$answer = new Answer($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_quiz'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $quiz_id = $quiz->create($title, $description, $_SESSION['user_id']);
    header("Location: admin.php?quiz_id=$quiz_id");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question_and_answers'])) {
    $quiz_id = $_POST['quiz_id'];
    $content = $_POST['content'];
    $question_id = $question->add($quiz_id, $content);

    for ($i = 1; $i <= 4; $i++) {
        $answer_content = $_POST["answer_$i"];
        $is_correct = isset($_POST["is_correct_$i"]) ? 1 : 0;
        $answer->add($question_id, $answer_content, $is_correct);
    }

    header("Location: admin.php?quiz_id=$quiz_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <form method="post">
        <label for="title">Titre du quiz:</label>
        <input type="text" id="title" name="title" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <button type="submit" name="create_quiz">Créer un quiz</button>
    </form>

    <h2>Vos Quiz</h2>
    <?php
    $quizzes = $quiz->getByUser($_SESSION['user_id']);
    foreach ($quizzes as $q) {
        echo '<a href="admin.php?quiz_id=' . $q['id'] . '">' . htmlspecialchars($q['title']) . '</a><br>';
        echo 'Created at: ' . $q['created_at'] . '<br>';
        echo htmlspecialchars($q['description']) . '<br><br>';
    }
    ?>

    <?php if (isset($_GET['quiz_id'])): ?>
        <h2>Ajouter une Question et des Réponses</h2>
        <form method="post">
            <input type="hidden" name="quiz_id" value="<?php echo $_GET['quiz_id']; ?>">
            <label for="content">Question:</label>
            <input type="text" id="content" name="content" required><br><br>
            
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <label for="answer_<?php echo $i; ?>">Réponse <?php echo $i; ?>:</label>
                <input type="text" id="answer_<?php echo $i; ?>" name="answer_<?php echo $i; ?>" required>
                <label for="is_correct_<?php echo $i; ?>">Correct:</label>
                <input type="checkbox" id="is_correct_<?php echo $i; ?>" name="is_correct_<?php echo $i; ?>"><br><br>
            <?php endfor; ?>
            
            <button type="submit" name="add_question_and_answers">Ajouter la question et les réponses</button>
        </form>
    <?php endif; ?>
</body>
</html>