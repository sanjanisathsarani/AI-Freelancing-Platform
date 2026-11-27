<?php require_once __DIR__ . '/../config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Projects & Services Freelancing Platform</title>
    <link rel="stylesheet" href="/ai_freelancing_platform/assets/style.css">

</head>
<body>
<nav class="navbar">
    <div class="logo"><strong>AI Freelance Hub</strong></div>
    <div>
        <a href="/ai_freelancing_platform/index.php">Browse Projects & Services</a>
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] === 'client'): ?>
                <a href="/ai_freelancing_platform/client/dashboard.php">Client Dashboard</a>
                <a href="/ai_freelancing_platform/client/post_project.php">Post Project</a>
            <?php else: ?>
                <a href="/ai_freelancing_platform/freelancer/dashboard.php">Freelancer Dashboard</a>
                <a href="/ai_freelancing_platform/freelancer/profile.php">My Skills & Services</a>
            <?php endif; ?>
            <a href="/ai_freelancing_platform/logout.php">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
        <?php else: ?>
            <a href="/ai_freelancing_platform/login.php">Login</a>
            <a href="/ai_freelancing_platform/register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>
<div class="container">
