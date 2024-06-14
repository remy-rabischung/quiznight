<?php
class Question {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($quizId, $text) {
        $stmt = $this->pdo->prepare('INSERT INTO questions (quiz_id, text) VALUES (?, ?)');
        $stmt->execute([$quizId, $text]);
        return $this->pdo->lastInsertId();
    }

    public function getByQuiz($quizId) {
        $stmt = $this->pdo->prepare('SELECT * FROM questions WHERE quiz_id = ?');
        $stmt->execute([$quizId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
