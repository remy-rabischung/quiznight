<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    foreach ($_POST as $question_id => $answer_id) {
        $question_id = str_replace('question_', '', $question_id);
        $stmt = $pdo->prepare('SELECT is_correct FROM answers WHERE id = ?');
        $stmt->execute([$answer_id]);
        $answer = $stmt->fetch();
        if ($answer['is_correct']) {
            $score++;
        }
    }
    echo "Votre score est : " . $score;
}
?>
