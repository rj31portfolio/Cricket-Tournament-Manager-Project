<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect('../admin/dashboard.php');
        } else {
            $_SESSION['error'] = "Invalid password";
            redirect('../admin/index.php');
        }
    } else {
        $_SESSION['error'] = "Admin not found";
        redirect('../admin/index.php');
    }
} else {
    redirect('../admin/index.php');
}
?>