<?php
session_start();
include '../config/database.php';
include '../config/functions.php';

if (!isset($_SESSION['team_id'])) {
    redirect('../team/index.php');
}

require_once '../vendor/autoload.php'; // Include Razorpay SDK

use Razorpay\Api\Api;

if (isset($_POST['payment_amount'])) {
    $team_id = $_SESSION['team_id'];
    $amount = sanitize($_POST['payment_amount']) * 100; // Convert to paise
    
    $api = new Api('YOUR_RAZORPAY_KEY_ID', 'YOUR_RAZORPAY_KEY_SECRET');
    
    $orderData = [
        'receipt'         => 'order_'.time(),
        'amount'          => $amount,
        'currency'        => 'INR',
        'payment_capture' => 1
    ];
    
    try {
        $razorpayOrder = $api->order->create($orderData);
        $razorpayOrderId = $razorpayOrder['id'];
        
        // Store this order ID in your database
        $sql = "UPDATE teams SET payment_order_id = '$razorpayOrderId' WHERE team_id = '$team_id'";
        mysqli_query($conn, $sql);
        
        $_SESSION['razorpay_order_id'] = $razorpayOrderId;
        
        $response = [
            'order_id' => $razorpayOrderId,
            'amount' => $amount,
            'team_id' => $team_id,
            'team_name' => $_SESSION['team_name'],
            'email' => 'team@example.com', // Replace with team email
            'contact' => '9999999999' // Replace with team contact
        ];
        
        echo json_encode($response);
    } catch (Exception $e) {
        $_SESSION['error'] = "Payment failed: " . $e->getMessage();
        redirect('../team/dashboard.php');
    }
} elseif (isset($_POST['razorpay_payment_id'])) {
    // Payment verification
    $team_id = $_SESSION['team_id'];
    $payment_id = sanitize($_POST['razorpay_payment_id']);
    $order_id = sanitize($_POST['razorpay_order_id']);
    $signature = sanitize($_POST['razorpay_signature']);
    
    $api = new Api('YOUR_RAZORPAY_KEY_ID', 'YOUR_RAZORPAY_KEY_SECRET');
    
    try {
        $attributes = [
            'razorpay_order_id' => $order_id,
            'razorpay_payment_id' => $payment_id,
            'razorpay_signature' => $signature
        ];
        
        $api->utility->verifyPaymentSignature($attributes);
        
        // Payment successful
        $sql = "UPDATE teams SET payment_status = 'Completed', payment_id = '$payment_id', status = 'Active' WHERE team_id = '$team_id'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "Payment successful! Your team is now active.";
            redirect('../team/dashboard.php');
        } else {
            $_SESSION['error'] = "Database error: " . mysqli_error($conn);
            redirect('../team/dashboard.php');
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Payment verification failed: " . $e->getMessage();
        redirect('../team/dashboard.php');
    }
} else {
    redirect('../team/dashboard.php');
}
?>