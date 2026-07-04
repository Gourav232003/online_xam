<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireCandidateAuth();
require_once __DIR__ . '/../config/database.php';

$examId = (int) ($_GET['exam_id'] ?? 0);
if ($examId <= 0) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, title, duration_minutes FROM exams WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $examId]);
$exam = $stmt->fetch();

if (!$exam) {
    header('Location: dashboard.php');
    exit;
}

$qStmt = $pdo->prepare('SELECT id, question_text, option_a, option_b, option_c, option_d FROM questions WHERE exam_id = :exam_id ORDER BY id');
$qStmt->execute(['exam_id' => $examId]);
$questions = $qStmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="page-shell container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><?php echo htmlspecialchars((string) $exam['title']); ?></h3>
        <span class="badge bg-primary p-2">Time: <span id="timer"></span></span>
    </div>

    <form method="post" action="submit_exam.php" id="examForm">
        <input type="hidden" name="exam_id" value="<?php echo (int) $examId; ?>">
        <?php foreach ($questions as $index => $question): ?>
            <div class="card p-3 mb-3 question-card">
                <h5>Q<?php echo $index + 1; ?>. <?php echo htmlspecialchars((string) $question['question_text']); ?></h5>
                <?php foreach (['A', 'B', 'C', 'D'] as $option): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[<?php echo (int) $question['id']; ?>]" value="<?php echo $option; ?>" id="q<?php echo (int) $question['id'] . $option; ?>">
                        <label class="form-check-label" for="q<?php echo (int) $question['id'] . $option; ?>">
                            (<?php echo $option; ?>) <?php echo htmlspecialchars((string) $question['option_' . strtolower($option)]); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <button class="btn btn-success" type="submit">Submit Exam</button>
    </form>
</div>

<script>
(function () {
    const totalSeconds = <?php echo (int) $exam['duration_minutes']; ?> * 60;
    let remaining = totalSeconds;
    const timerEl = document.getElementById('timer');

    function drawTimer() {
        const min = Math.floor(remaining / 60);
        const sec = remaining % 60;
        timerEl.textContent = String(min).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
    }

    drawTimer();
    const interval = setInterval(function () {
        remaining -= 1;
        drawTimer();
        if (remaining <= 0) {
            clearInterval(interval);
            document.getElementById('examForm').submit();
        }
    }, 1000);
})();
</script>
</body>
</html>
