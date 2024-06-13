<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$host = 'localhost';
$dbname = 'quiz_night';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement du formulaire de création de quiz
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_quiz'])) {
    $title = $_POST['title'];
    $stmt = $pdo->prepare('INSERT INTO quizzes (title, created_by) VALUES (?, ?)');
    $stmt->execute([$title, $_SESSION['user_id']]);
    $quiz_id = $pdo->lastInsertId();

    header("Location: admin.php?quiz_id=$quiz_id");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    $quiz_id = $_POST['quiz_id'];
    $content = $_POST['content'];
    $stmt = $pdo->prepare('INSERT INTO questions (quiz_id, content) VALUES (?, ?)');
    $stmt->execute([$quiz_id, $content]);
    $question_id = $pdo->lastInsertId();

    header("Location: admin.php?quiz_id=$quiz_id&question_id=$question_id");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_answer'])) {
    $question_id = $_POST['question_id'];
    $content = $_POST['content'];
    $is_correct = isset($_POST['is_correct']) ? 1 : 0;
    $stmt = $pdo->prepare('INSERT INTO answers (question_id, content, is_correct) VALUES (?, ?, ?)');
    $stmt->execute([$question_id, $content, $is_correct]);
    $quiz_id = $_POST['quiz_id'];
    header("Location: admin.php?quiz_id=$quiz_id&question_id=$question_id");
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
        <button type="submit" name="create_quiz">Créer un quiz</button>
    </form>

    <!-- Ajout de la liste des quiz créés par l'utilisateur connecté -->
    <h2>Vos Quiz</h2>
    <?php
    $stmt = $pdo->prepare('SELECT * FROM quizzes WHERE created_by = ?');
    $stmt->execute([$_SESSION['user_id']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<a href="admin.php?quiz_id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a><br>';
    }
    ?>

    <?php if (isset($_GET['quiz_id'])): ?>
        <h2>Ajouter une Question</h2>
        <form method="post">
            <input type="hidden" name="quiz_id" value="<?php echo $_GET['quiz_id']; ?>">
            <label for="content">Contenu de la question:</label>
            <input type="text" id="content" name="content" required>
            <button type="submit" name="add_question">Ajouter une question</button>
        </form>
    <?php endif; ?>

    <?php if (isset($_GET['question_id'])): ?>
        <h2>Ajouter une Réponse</h2>
        <form method="post">
            <input type="hidden" name="question_id" value="<?php echo $_GET['question_id']; ?>">
            <input type="hidden" name="quiz_id" value="<?php echo $_GET['quiz_id']; ?>">
            <label for="content">Contenu de la réponse:</label>
            <input type="text" id="content" name="content" required>
            <label for="is_correct">Correct:</label>
            <input type="checkbox" id="is_correct" name="is_correct">
            <button type="submit" name="add_answer">Ajouter une réponse</button>
        </form>
    <?php endif; ?>
</body>
</html>
