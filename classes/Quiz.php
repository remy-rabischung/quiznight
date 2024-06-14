<?php
class Quiz {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($title, $description, $createdBy) {
        $stmt = $this->pdo->prepare('INSERT INTO quizzes (title, description, created_by, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$title, $description, $createdBy]);
        return $this->pdo->lastInsertId();
    }

    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM quizzes');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUser($userId) {
        $stmt = $this->pdo->prepare('SELECT * FROM quizzes WHERE created_by = ?');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
