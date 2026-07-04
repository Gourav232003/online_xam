<?php

declare(strict_types=1);

session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = trim($_POST['user_id'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT id, user_id, full_name, password FROM candidates WHERE user_id = :user_id LIMIT 1');
    $stmt->execute(['user_id' => $userId]);
    $candidate = $stmt->fetch();

    if ($candidate && $candidate['password'] === $password) {
        $_SESSION['candidate_id'] = (int) $candidate['id'];
        $_SESSION['candidate_name'] = $candidate['full_name'];
        $_SESSION['candidate_user_id'] = $candidate['user_id'];

        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid User ID or Password.';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Candidate Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="card auth-card p-4">
    <h3 class="mb-3 text-center">Candidate Login</h3>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">User ID</label>
            <input type="text" name="user_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <a href="../admin/login.php" class="mt-3 text-center">Admin Login</a>
</div>
</body>
</html>
