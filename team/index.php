<?php 
include '../includes/header.php';
include '../config/functions.php';

if (isset($_SESSION['team_id'])) {
    redirect('dashboard.php');
}
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Team Login / Register</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="teamTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button">Register</button>
                    </li>
                </ul>
                
                <div class="tab-content p-3" id="teamTabContent">
                    <!-- Login Form -->
                    <div class="tab-pane fade show active" id="login" role="tabpanel">
                        <form action="../processes/login_team.php" method="POST">
                            <div class="mb-3">
                                <label for="team_id" class="form-label">Team ID</label>
                                <input type="text" class="form-control" id="team_id" name="team_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                    
                    <!-- Registration Form -->
                    <div class="tab-pane fade" id="register" role="tabpanel">
                        <form action="../processes/register_team.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="team_name" class="form-label">Team Name</label>
                                <input type="text" class="form-control" id="team_name" name="team_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="captain_name" class="form-label">Captain Name</label>
                                <input type="text" class="form-control" id="captain_name" name="captain_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="contact" name="contact" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="logo" class="form-label">Team Logo (Optional)</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-success">Register Team</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>