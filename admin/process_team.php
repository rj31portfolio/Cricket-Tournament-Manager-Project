<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

if (isset($_GET['approve'])) {
    $team_id = sanitize($_GET['approve']);
    $sql = "UPDATE teams SET status = 'Active' WHERE team_id = '$team_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Team approved successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    redirect('teams.php');
} elseif (isset($_GET['reject'])) {
    $team_id = sanitize($_GET['reject']);
    $sql = "UPDATE teams SET status = 'Blocked' WHERE team_id = '$team_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Team rejected!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    redirect('teams.php');
} elseif (isset($_GET['block'])) {
    $team_id = sanitize($_GET['block']);
    $sql = "UPDATE teams SET status = 'Blocked' WHERE team_id = '$team_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Team blocked successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    redirect('teams.php');
} elseif (isset($_GET['activate'])) {
    $team_id = sanitize($_GET['activate']);
    $sql = "UPDATE teams SET status = 'Active' WHERE team_id = '$team_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Team activated successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    redirect('teams.php');
} else {
    redirect('teams.php');
}
?>