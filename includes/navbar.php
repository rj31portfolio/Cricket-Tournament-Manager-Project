<?php
session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <img src="../assets/images/logo.png" alt="Logo" height="30" class="d-inline-block align-top">
            Cricket TM
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['team_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../team/dashboard.php">Team Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../processes/logout.php">Logout</a>
                    </li>
                <?php elseif (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../player/dashboard.php">Player Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../processes/logout.php">Logout</a>
                    </li>
                <?php elseif (isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/dashboard.php">Admin Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../processes/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../team/index.php">Team Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../player/index.php">Player Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>