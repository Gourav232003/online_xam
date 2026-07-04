<?php

declare(strict_types=1);

session_start();
unset($_SESSION['admin_id'], $_SESSION['admin_username']);

header('Location: login.php');
exit;
