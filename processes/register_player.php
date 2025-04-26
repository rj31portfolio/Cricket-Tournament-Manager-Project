<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $player_name = sanitize($_POST['player_name']);
    $email = sanitize($_POST['email']);
    $contact = sanitize($_POST['contact']);
    $team_id = sanitize($_POST['team_id']);
    $player_role = sanitize($_POST['player_role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Handle file upload
    $profile_pic = '';
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../assets/uploads/profile_pics/";
        $file_ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['profile_pic']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                $profile_pic = $new_filename;
            }
        }
    } else {
        $profile_pic = 'default-profile.jpg';
    }
    
    // Check if email already exists
    $check_email = "SELECT * FROM players WHERE email = '$email'";
    if (mysqli_num_rows(mysqli_query($conn, $check_email)) > 0) {
        $_SESSION['error'] = "Email already registered!";
        redirect('../player/index.php');
    }
    
    $sql = "INSERT INTO players (player_name, email, contact, team_id, player_role, password, profile_pic) 
            VALUES ('$player_name', '$email', '$contact', '$team_id', '$player_role', '$password', '$profile_pic')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Player registered successfully!";
        redirect('../player/index.php');
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
        redirect('../player/index.php');
    }
} else {
    redirect('../player/index.php');
}
?>