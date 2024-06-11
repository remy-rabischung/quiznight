<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Gestion des formulaires de création et de modification de quiz
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_quiz'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $user_id = $_SESSION['user_id'];

        $sql = "INSERT INTO quizzes (title, description, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $description, $user_id);
        $stmt->execute();
    } elseif (isset($_POST['update_quiz'])) {
        $quiz_id = $_POST['quiz_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];

        $sql = "UPDATE quizzes SET title = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $description, $quiz_id);
        $stmt->execute();
    } elseif (isset($_POST['add_question'])) {
        $quiz_id = $_POST['quiz_id'];
        $question_text = $_POST['question_text'];

        $sql = "INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $quiz_id, $question_text);
        $stmt->execute();
    } elseif (isset($_POST['add_answer'])) {
        $question_id = $_POST['question_id'];
        $answer_text = $_POST['answer_text'];
        $is_correct = isset($_POST['is_correct']) ? 1 : 0;

        $sql = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $question_id, $answer_text, $is_correct);
        $stmt->execute();
    }
}

// Récupérer tous les quiz de l'utilisateur
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM quizzes WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Définir le titre de la page
$pageTitle = "Admin - Gérer les Quiz";
include '../includes/header.php'; // Inclure le fichier d'en-tête
?>

<h1>Gérer les Quiz</h1>

<h2>Créer un nouveau quiz</h2>
<form method="POST" action="admin.php">
    <label for="title">Titre:</label>
    <input type="text" id="title" name="title" required><br>
    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea><br>
    <button type="submit" name="create_quiz">Créer le quiz</button>
</form>

<h2>Modifier un quiz existant</h2>
<ul>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "<form method='POST' action='admin.php'>";
            echo "<input type='hidden' name='quiz_id' value='" . $row['id'] . "'>";
            echo "<label for='title'>Titre:</label>";
            echo "<input type='text' name='title' value='" . htmlspecialchars($row['title']) . "' required><br>";
            echo "<label for='description'>Description:</label>";
            echo "<textarea name='description' required>" . htmlspecialchars($row['description']) . "</textarea><br>";
            echo "<button type='submit' name='update_quiz'>Mettre à jour</button>";
            echo "</form>";
            
            // Formulaire pour ajouter des questions
            echo "<form method='POST' action='admin.php'>";
            echo "<input type='hidden' name='quiz_id' value='" . $row['id'] . "'>";
            echo "<label for='question_text'>Question:</label>";
            echo "<input type='text' name='question_text' required><br>";
            echo "<button type='submit' name='add_question'>Ajouter une question</button>";
            echo "</form>";

            // Récupérer et afficher les questions du quiz
            $quiz_id = $row['id'];
            $sql_questions = "SELECT * FROM questions WHERE quiz_id = ?";
            $stmt_questions = $conn->prepare($sql_questions);
            $stmt_questions->bind_param("i", $quiz_id);
            $stmt_questions->execute();
            $result_questions = $stmt_questions->get_result();

            if ($result_questions->num_rows > 0) {
                while ($row_question = $result_questions->fetch_assoc()) {
                    echo "<ul>";
                    echo "<li>";
                    echo htmlspecialchars($row_question['question_text']);

                    // Formulaire pour ajouter des réponses
                    echo "<form method='POST' action='admin.php'>";
                    echo "<input type='hidden' name='question_id' value='" . $row_question['id'] . "'>";
                    echo "<label for='answer_text'>Réponse:</label>";
                    echo "<input type='text' name='answer_text' required><br>";
                    echo "<label for='is_correct'>Bonne réponse:</label>";
                    echo "<input type='checkbox' name='is_correct'><br>";
                    echo "<button type='submit' name='add_answer'>Ajouter une réponse</button>";
                    echo "</form>";

                    // Récupérer et afficher les réponses de la question
                    $question_id = $row_question['id'];
                    $sql_answers = "SELECT * FROM answers WHERE question_id = ?";
                    $stmt_answers = $conn->prepare($sql_answers);
                    $stmt_answers->bind_param("i", $question_id);
                    $stmt_answers->execute();
                    $result_answers = $stmt_answers->get_result();

                    if ($result_answers->num_rows > 0) {
                        echo "<ul>";
                        while ($row_answer = $result_answers->fetch_assoc()) {
                            echo "<li>";
                            echo htmlspecialchars($row_answer['answer_text']);
                            if ($row_answer['is_correct']) {
                                echo " (Correcte)";
                            }
                            echo "</li>";
                        }
                        echo "</ul>";
                    }

                    echo "</li>";
                    echo "</ul>";
                }
            }
            
            echo "</li>";
        }
    } else {
        echo "<li>Aucun quiz trouvé.</li>";
    }
    ?>
</ul>

<?php include '../includes/footer.php'; // Inclure le fichier de pied de page ?>

<?php
$conn->close();
?>
