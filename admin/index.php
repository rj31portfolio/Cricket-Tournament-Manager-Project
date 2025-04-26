<?php
include '../includes/header.php';
include '../config/functions.php';

if (isset($_SESSION['admin_id'])) {
    redirect('dashboard.php');
}
?>

<div class="row">
    <div class="col-md-4 mx-auto">
        <div class="card mt-5">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">Admin Login</h4>
            </div>
            <div class="card-body">
                <form action="../processes/login_admin.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>