<?php
class Quiz {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getQuizById($id) {
        $stmt = $this->db->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getQuestionsByQuizId($id) {
        $stmt = $this->db->prepare("SELECT * FROM questions WHERE quiz_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
        return $questions;
    }

    public function getAnswersByQuestionId($question_id) {
        $stmt = $this->db->prepare("SELECT * FROM answers WHERE question_id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $answers = [];
        while ($row = $result->fetch_assoc()) {
            $answers[] = $row;
        }
        return $answers;
    }
}
?>
