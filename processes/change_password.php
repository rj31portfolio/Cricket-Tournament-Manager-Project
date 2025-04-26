<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('../index.php');
}

$type = sanitize($_POST['type']);
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if ($new_password != $confirm_password) {
    $_SESSION['error'] = "New passwords don't match!";
    redirect_back();
}

if ($type == 'team') {
    if (!isset($_SESSION['team_id'])) {
        redirect('../team/index.php');
    }
    
    $team_id = $_SESSION['team_id'];
    $sql = "SELECT password FROM teams WHERE team_id = '$team_id'";
    $team = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    
    if (!password_verify($current_password, $team['password'])) {
        $_SESSION['error'] = "Current password is incorrect!";
        redirect_back();
    }
    
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_sql = "UPDATE teams SET password = '$hashed_password' WHERE team_id = '$team_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['success'] = "Password changed successfully!";
        redirect('../team/profile.php');
    } else {
        $_SESSION['error'] = "Error changing password: " . mysqli_error($conn);
        redirect_back();
    }
} elseif ($type == 'player') {
    if (!isset($_SESSION['user_id'])) {
        redirect('../player/index.php');
    }
    
    $player_id = $_SESSION['user_id'];
    $sql = "SELECT password FROM players WHERE id = '$player_id'";
    $player = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    
    if (!password_verify($current_password, $player['password'])) {
        $_SESSION['error'] = "Current password is incorrect!";
        redirect_back();
    }
    
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_sql = "UPDATE players SET password = '$hashed_password' WHERE id = '$player_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['success'] = "Password changed successfully!";
        redirect('../player/profile.php');
    } else {
        $_SESSION['error'] = "Error changing password: " . mysqli_error($conn);
        redirect_back();
    }
} else {
    redirect('../index.php');
}

function redirect_back() {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>