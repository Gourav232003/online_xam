<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireAdminAuth();
require_once __DIR__ . '/../config/database.php';

$totalCandidates = (int) $pdo->query('SELECT COUNT(*) FROM candidates')->fetchColumn();
$totalQuestions = (int) $pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn();
$totalExams = (int) $pdo->query('SELECT COUNT(*) FROM exams')->fetchColumn();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Admin Dashboard</h3>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card p-3"><h5><?php echo $totalCandidates; ?></h5><p class="mb-0">Candidates</p></div></div>
        <div class="col-md-4"><div class="card p-3"><h5><?php echo $totalQuestions; ?></h5><p class="mb-0">Questions</p></div></div>
        <div class="col-md-4"><div class="card p-3"><h5><?php echo $totalExams; ?></h5><p class="mb-0">Exams</p></div></div>
    </div>

    <a href="questions.php" class="btn btn-primary">Manage Questions</a>
</div>
</body>
</html>
