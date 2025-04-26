<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_name = sanitize($_POST['team_name']);
    $captain_name = sanitize($_POST['captain_name']);
    $contact = sanitize($_POST['contact']);
    $email = sanitize($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $team_id = generateTeamID();
    $status = 'Pending';
    
    // Handle file upload
    $logo_path = '';
    if (!empty($_FILES['logo']['name'])) {
        $target_dir = "../assets/uploads/team_logos/";
        $file_ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $new_filename = $team_id . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['logo']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
                $logo_path = $new_filename;
            }
        }
    } else {
        $logo_path = 'default-team-logo.png';
    }
    
    $sql = "INSERT INTO teams (team_id, team_name, captain_name, contact, email, password, logo, status) 
            VALUES ('$team_id', '$team_name', '$captain_name', '$contact', '$email', '$password', '$logo_path', '$status')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Team registered successfully! Your Team ID is: $team_id";
        redirect('../team/index.php');
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
        redirect('../team/index.php');
    }
} else {
    redirect('../team/index.php');
}
?>