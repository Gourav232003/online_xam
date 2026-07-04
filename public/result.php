<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireCandidateAuth();
require_once __DIR__ . '/../config/database.php';

$examId = (int) ($_GET['exam_id'] ?? 0);
$candidateId = (int) $_SESSION['candidate_id'];

if ($examId <= 0) {
    header('Location: dashboard.php');
    exit;
}

$totalStmt = $pdo->prepare('SELECT COUNT(*) AS total FROM questions WHERE exam_id = :exam_id');
$totalStmt->execute(['exam_id' => $examId]);
$totalQuestions = (int) $totalStmt->fetch()['total'];

$scoreStmt = $pdo->prepare('SELECT COUNT(*) AS score FROM answers WHERE exam_id = :exam_id AND candidate_id = :candidate_id AND is_correct = 1');
$scoreStmt->execute(['exam_id' => $examId, 'candidate_id' => $candidateId]);
$score = (int) $scoreStmt->fetch()['score'];

$percentage = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="page-shell container">
    <div class="card p-4 text-center">
        <h3>Exam Result</h3>
        <p class="lead mb-1">Score: <?php echo $score; ?> / <?php echo $totalQuestions; ?></p>
        <p class="fw-bold">Percentage: <?php echo $percentage; ?>%</p>
        <a href="dashboard.php" class="btn btn-primary mt-2">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
