<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM players WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $player = mysqli_fetch_assoc($result);
        if (password_verify($password, $player['password'])) {
            $_SESSION['user_id'] = $player['id'];
            $_SESSION['player_name'] = $player['player_name'];
            $_SESSION['team_id'] = $player['team_id'];
            redirect('../player/dashboard.php');
        } else {
            $_SESSION['error'] = "Invalid password";
            redirect('../player/index.php');
        }
    } else {
        $_SESSION['error'] = "Player not found";
        redirect('../player/index.php');
    }
} else {
    redirect('../player/index.php');
}
?>