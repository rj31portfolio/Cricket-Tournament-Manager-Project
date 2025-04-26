<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_id = sanitize($_POST['team_id']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM teams WHERE team_id = '$team_id' AND status = 'Active'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $team = mysqli_fetch_assoc($result);
        if (password_verify($password, $team['password'])) {
            $_SESSION['team_id'] = $team['team_id'];
            $_SESSION['team_name'] = $team['team_name'];
            redirect('../team/dashboard.php');
        } else {
            $_SESSION['error'] = "Invalid password";
            redirect('../team/index.php');
        }
    } else {
        $_SESSION['error'] = "Team not found or not approved yet";
        redirect('../team/index.php');
    }
} else {
    redirect('../team/index.php');
}
?>