<?php
class Answer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($questionId, $text, $isCorrect) {
        $stmt = $this->pdo->prepare('INSERT INTO answers (question_id, text, is_correct) VALUES (?, ?, ?)');
        $stmt->execute([$questionId, $text, $isCorrect]);
    }

    public function getByQuestion($questionId) {
        $stmt = $this->pdo->prepare('SELECT * FROM answers WHERE question_id = ?');
        $stmt->execute([$questionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
