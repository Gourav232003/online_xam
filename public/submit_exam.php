<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireCandidateAuth();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$candidateId = (int) $_SESSION['candidate_id'];
$examId = (int) ($_POST['exam_id'] ?? 0);
$answers = $_POST['answers'] ?? [];

if ($examId <= 0) {
    header('Location: dashboard.php');
    exit;
}

$qStmt = $pdo->prepare('SELECT id, correct_option FROM questions WHERE exam_id = :exam_id');
$qStmt->execute(['exam_id' => $examId]);
$questions = $qStmt->fetchAll();

$insert = $pdo->prepare(
    'INSERT INTO answers (candidate_id, exam_id, question_id, selected_option, is_correct)
     VALUES (:candidate_id, :exam_id, :question_id, :selected_option, :is_correct)
     ON DUPLICATE KEY UPDATE selected_option = VALUES(selected_option), is_correct = VALUES(is_correct)'
);

foreach ($questions as $question) {
    $questionId = (int) $question['id'];
    $selected = $answers[$questionId] ?? null;

    if ($selected !== null && !in_array($selected, ['A', 'B', 'C', 'D'], true)) {
        $selected = null;
    }

    $isCorrect = ($selected !== null && $selected === $question['correct_option']) ? 1 : 0;

    $insert->execute([
        'candidate_id' => $candidateId,
        'exam_id' => $examId,
        'question_id' => $questionId,
        'selected_option' => $selected,
        'is_correct' => $isCorrect,
    ]);
}

header('Location: result.php?exam_id=' . $examId);
exit;
