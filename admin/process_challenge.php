<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

if (isset($_GET['complete'])) {
    $challenge_id = sanitize($_GET['complete']);
    
    $sql = "UPDATE challenges SET status = 'Completed' WHERE id = '$challenge_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Challenge marked as completed!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
} elseif (isset($_GET['delete'])) {
    $challenge_id = sanitize($_GET['delete']);
    
    $sql = "DELETE FROM challenges WHERE id = '$challenge_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Challenge deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
}

redirect('challenges.php');
?>