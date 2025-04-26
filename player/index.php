<?php 
include '../includes/header.php';
include '../config/functions.php';

if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php');
}
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Player Login / Register</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="playerTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button">Register</button>
                    </li>
                </ul>
                
                <div class="tab-content p-3" id="playerTabContent">
                    <!-- Login Form -->
                    <div class="tab-pane fade show active" id="login" role="tabpanel">
                        <form action="../processes/login_player.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-success">Login</button>
                        </form>
                    </div>
                    
                    <!-- Registration Form -->
                    <div class="tab-pane fade" id="register" role="tabpanel">
                        <form action="../processes/register_player.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="player_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="player_name" name="player_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="contact" name="contact" required>
                            </div>
                            <div class="mb-3">
                                <label for="team_id" class="form-label">Team</label>
                                <select class="form-select" id="team_id" name="team_id" required>
                                    <option value="">-- Select Team --</option>
                                    <?php
                                    $sql = "SELECT team_id, team_name FROM teams WHERE status = 'Active'";
                                    $result = mysqli_query($conn, $sql);
                                    while($team = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$team['team_id']}'>{$team['team_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="player_role" class="form-label">Playing Role</label>
                                <select class="form-select" id="player_role" name="player_role" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="Batsman">Batsman</option>
                                    <option value="Bowler">Bowler</option>
                                    <option value="All-rounder">All-rounder</option>
                                    <option value="Wicket-keeper">Wicket-keeper</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="profile_pic" class="form-label">Profile Picture (Optional)</label>
                                <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-success">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>