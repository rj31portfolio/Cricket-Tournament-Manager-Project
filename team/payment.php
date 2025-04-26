<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['team_id'])) {
    redirect('../team/index.php');
}

$team_id = $_SESSION['team_id'];
$sql = "SELECT * FROM teams WHERE team_id = '$team_id'";
$team = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Get tournament entry fee
$settings_sql = "SELECT * FROM tournament_settings LIMIT 1";
$settings = mysqli_fetch_assoc(mysqli_query($conn, $settings_sql));

// Check if payment is already done
if ($team['payment_status'] == 'Completed') {
    $_SESSION['info'] = "Your payment is already completed. Team is active.";
    redirect('dashboard.php');
}
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Pay Entry Fee</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['info'])): ?>
                    <div class="alert alert-info"><?php echo $_SESSION['info']; unset($_SESSION['info']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <div class="text-center mb-4">
                    <h4>Tournament Entry Fee</h4>
                    <h1>₹<?php echo $settings['entry_fee']; ?></h1>
                </div>
                
                <form id="paymentForm">
                    <input type="hidden" id="teamId" value="<?php echo $team['team_id']; ?>">
                    <input type="hidden" id="teamName" value="<?php echo $team['team_name']; ?>">
                    <input type="hidden" id="entryFee" value="<?php echo $settings['entry_fee']; ?>">
                    
                    <div class="d-grid">
                        <button type="button" id="paymentButton" class="btn btn-primary btn-lg">
                            Pay Now with Razorpay
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 text-center">
                    <p class="text-muted">By completing this payment, you agree to our tournament rules and regulations.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Razorpay checkout script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<?php include '../includes/footer.php'; ?>