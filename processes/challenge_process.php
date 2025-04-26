<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if (!isset($_SESSION['team_id'])) {
    redirect('../team/index.php');
}

if (isset($_POST['challenged_id'])) {
    // New challenge
    $challenger_id = $_SESSION['team_id'];
    $challenged_id = sanitize($_POST['challenged_id']);
    $match_date = sanitize($_POST['match_date']);
    $venue = sanitize($_POST['venue']);
    $status = 'Pending';
    
    $sql = "INSERT INTO challenges (challenger_id, challenged_id, match_date, venue, status) 
            VALUES ('$challenger_id', '$challenged_id', '$match_date', '$venue', '$status')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Challenge sent successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    redirect('../team/dashboard.php');
} elseif (isset($_GET['accept'])) {
    // Accept challenge
    $challenge_id = sanitize($_GET['accept']);
    $team_id = $_SESSION['team_id'];
    
    $sql = "UPDATE challenges SET status = 'Accepted' WHERE id = '$challenge_id' AND challenged_id = '$team_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Challenge accepted!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    redirect('../team/dashboard.php');
} elseif (isset($_GET['reject'])) {
    // Reject challenge
    $challenge_id = sanitize($_GET['reject']);
    $team_id = $_SESSION['team_id'];
    
    $sql = "UPDATE challenges SET status = 'Rejected' WHERE id = '$challenge_id' AND challenged_id = '$team_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Challenge rejected.";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    redirect('../team/dashboard.php');
} else {
    redirect('../team/dashboard.php');
}
?>