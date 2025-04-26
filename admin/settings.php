<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

// Get current settings
$settings_sql = "SELECT * FROM tournament_settings LIMIT 1";
$settings = mysqli_fetch_assoc(mysqli_query($conn, $settings_sql));

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entry_fee = sanitize($_POST['entry_fee']);
    
    $sql = "UPDATE tournament_settings SET entry_fee = '$entry_fee'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Settings updated successfully!";
        $settings['entry_fee'] = $entry_fee;
    } else {
        $_SESSION['error'] = "Error updating settings: " . mysqli_error($conn);
    }
}
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5>Tournament Settings</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Entry Fee (₹)</label>
                        <input type="number" class="form-control" name="entry_fee" value="<?php echo $settings['entry_fee']; ?>" step="0.01" min="0" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>