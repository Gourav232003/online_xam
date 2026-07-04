<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireAdminAuth();
require_once __DIR__ . '/../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $stmt = $pdo->prepare(
            'INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option)
             VALUES (:exam_id, :question_text, :option_a, :option_b, :option_c, :option_d, :correct_option)'
        );
        $stmt->execute([
            'exam_id' => (int) ($_POST['exam_id'] ?? 1),
            'question_text' => trim($_POST['question_text'] ?? ''),
            'option_a' => trim($_POST['option_a'] ?? ''),
            'option_b' => trim($_POST['option_b'] ?? ''),
            'option_c' => trim($_POST['option_c'] ?? ''),
            'option_d' => trim($_POST['option_d'] ?? ''),
            'correct_option' => $_POST['correct_option'] ?? 'A',
        ]);
        $message = 'Question added.';
    }

    if ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM questions WHERE id = :id');
        $stmt->execute(['id' => (int) ($_POST['id'] ?? 0)]);
        $message = 'Question deleted.';
    }

    if ($action === 'update') {
        $stmt = $pdo->prepare(
            'UPDATE questions
             SET question_text = :question_text, option_a = :option_a, option_b = :option_b,
                 option_c = :option_c, option_d = :option_d, correct_option = :correct_option
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => (int) ($_POST['id'] ?? 0),
            'question_text' => trim($_POST['question_text'] ?? ''),
            'option_a' => trim($_POST['option_a'] ?? ''),
            'option_b' => trim($_POST['option_b'] ?? ''),
            'option_c' => trim($_POST['option_c'] ?? ''),
            'option_d' => trim($_POST['option_d'] ?? ''),
            'correct_option' => $_POST['correct_option'] ?? 'A',
        ]);
        $message = 'Question updated.';
    }
}

$questions = $pdo->query('SELECT id, question_text, option_a, option_b, option_c, option_d, correct_option FROM questions ORDER BY id DESC')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Manage Questions</h3>
        <a href="dashboard.php" class="btn btn-outline-secondary">Back</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="card p-3 mb-4">
        <h5>Add Question</h5>
        <form method="post" class="row g-2">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="exam_id" value="1">
            <div class="col-12"><input name="question_text" class="form-control" placeholder="Question" required></div>
            <div class="col-md-6"><input name="option_a" class="form-control" placeholder="Option A" required></div>
            <div class="col-md-6"><input name="option_b" class="form-control" placeholder="Option B" required></div>
            <div class="col-md-6"><input name="option_c" class="form-control" placeholder="Option C" required></div>
            <div class="col-md-6"><input name="option_d" class="form-control" placeholder="Option D" required></div>
            <div class="col-md-4">
                <select name="correct_option" class="form-select">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            <div class="col-md-8"><button class="btn btn-primary w-100" type="submit">Add</button></div>
        </form>
    </div>

    <div class="card p-3">
        <h5>Existing Questions</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Question</th>
                        <th>Correct</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $question): ?>
                        <tr>
                            <td><?php echo (int) $question['id']; ?></td>
                            <td>
                                <form method="post" class="d-grid gap-2">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?php echo (int) $question['id']; ?>">
                                    <input name="question_text" class="form-control" value="<?php echo htmlspecialchars((string) $question['question_text']); ?>" required>
                                    <div class="row g-2">
                                        <div class="col-md-6"><input name="option_a" class="form-control" value="<?php echo htmlspecialchars((string) $question['option_a']); ?>" required></div>
                                        <div class="col-md-6"><input name="option_b" class="form-control" value="<?php echo htmlspecialchars((string) $question['option_b']); ?>" required></div>
                                        <div class="col-md-6"><input name="option_c" class="form-control" value="<?php echo htmlspecialchars((string) $question['option_c']); ?>" required></div>
                                        <div class="col-md-6"><input name="option_d" class="form-control" value="<?php echo htmlspecialchars((string) $question['option_d']); ?>" required></div>
                                    </div>
                                    <select name="correct_option" class="form-select">
                                        <?php foreach (['A', 'B', 'C', 'D'] as $option): ?>
                                            <option value="<?php echo $option; ?>" <?php echo $question['correct_option'] === $option ? 'selected' : ''; ?>><?php echo $option; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-warning" type="submit">Update</button>
                                </form>
                            </td>
                            <td><?php echo htmlspecialchars((string) $question['correct_option']); ?></td>
                            <td>
                                <form method="post" onsubmit="return confirm('Delete this question?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo (int) $question['id']; ?>">
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
