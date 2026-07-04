<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireCandidateAuth(): void
{
    if (empty($_SESSION['candidate_id'])) {
        header('Location: ../public/index.php');
        exit;
    }
}

function requireAdminAuth(): void
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: ../admin/login.php');
        exit;
    }
}
