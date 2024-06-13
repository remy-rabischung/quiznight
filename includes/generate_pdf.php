<?php
require_once('../config/database.php');
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

if (!isset($_GET['id'])) {
    die("ID de quiz manquant.");
}

$quizId = $_GET['id'];

function getQuestionsByQuizId($quizId) {
    global $conn;
    $sql = "SELECT q.question_text, a.answer_text, a.is_correct 
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
            $question_text = $row['question_text'];
            if (!isset($questions[$question_text])) {
                $questions[$question_text] = [];
            }
            $questions[$question_text][] = [
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

if (empty($questions)) {
    die("Aucune question trouvée pour ce quiz.");
}

// Create new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Votre Nom');
$pdf->SetTitle('Quiz');
$pdf->SetSubject('Quiz Pub');
$pdf->SetKeywords('TCPDF, PDF, quiz, pub, question');

// Add a page
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'Quiz', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 12);

foreach ($questions as $question_text => $answers) {
    $pdf->Ln();
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->MultiCell(0, 10, $question_text, 0, 'L', 0, 1);

    $pdf->SetFont('helvetica', '', 12);
    foreach ($answers as $answer) {
        $correct = $answer['is_correct'] ? ' (Correct)' : '';
        $pdf->MultiCell(0, 10, '- ' . $answer['answer_text'] . $correct, 0, 'L', 0, 1);
    }
}

// Close and output PDF document
$pdf->Output('quiz.pdf', 'I');
?>
