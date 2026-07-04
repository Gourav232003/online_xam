<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireCandidateAuth();
require_once __DIR__ . '/../config/database.php';

$exam = $pdo->query('SELECT id, title, duration_minutes, start_time, end_time FROM exams ORDER BY id DESC LIMIT 1')->fetch();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Candidate Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="page-shell container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Welcome, <?php echo htmlspecialchars((string) $_SESSION['candidate_name']); ?></h3>
            <small class="text-muted">User ID: <?php echo htmlspecialchars((string) $_SESSION['candidate_user_id']); ?></small>
        </div>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>

    <div class="card p-4">
        <h4><?php echo htmlspecialchars((string) ($exam['title'] ?? 'No Active Exam')); ?></h4>
        <?php if ($exam): ?>
            <p class="mb-1">Duration: <?php echo (int) $exam['duration_minutes']; ?> minutes</p>
            <p class="mb-1">Start: <?php echo htmlspecialchars((string) $exam['start_time']); ?></p>
            <p class="mb-3">End: <?php echo htmlspecialchars((string) $exam['end_time']); ?></p>
            <a href="exam.php?exam_id=<?php echo (int) $exam['id']; ?>" class="btn btn-primary">Start Exam</a>
        <?php else: ?>
            <div class="alert alert-warning">No exam available.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
