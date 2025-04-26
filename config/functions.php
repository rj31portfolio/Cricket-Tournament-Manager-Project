<?php
include 'database.php';

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
}

function generateTeamID() {
    return 'TM' . strtoupper(substr(md5(uniqid()), 0, 6));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['team_id']) || isset($_SESSION['admin_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>
