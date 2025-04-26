<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    redirect('../player/index.php');
}

$player_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $player_name = sanitize($_POST['player_name']);
    $contact = sanitize($_POST['contact']);
    $player_role = sanitize($_POST['player_role']);
    
    // Handle file upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../assets/uploads/profile_pics/";
        $file_ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['profile_pic']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                // Delete old profile pic if it's not the default
                $old_pic_sql = "SELECT profile_pic FROM players WHERE id = '$player_id'";
                $old_pic = mysqli_fetch_assoc(mysqli_query($conn, $old_pic_sql))['profile_pic'];
                if ($old_pic != 'default-profile.jpg') {
                    unlink($target_dir . $old_pic);
                }
                
                $sql = "UPDATE players SET player_name = '$player_name', contact = '$contact', 
                        player_role = '$player_role', profile_pic = '$new_filename' 
                        WHERE id = '$player_id'";
            }
        }
    } else {
        $sql = "UPDATE players SET player_name = '$player_name', contact = '$contact', 
                player_role = '$player_role' 
                WHERE id = '$player_id'";
    }
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Profile updated successfully!";
        $_SESSION['player_name'] = $player_name;
        redirect('profile.php');
    } else {
        $_SESSION['error'] = "Error updating profile: " . mysqli_error($conn);
    }
}

// Get player data
$sql = "SELECT p.*, t.team_name 
        FROM players p
        JOIN teams t ON p.team_id = t.team_id
        WHERE p.id = '$player_id'";
$player = mysqli_fetch_assoc(mysqli_query($conn, $sql));
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Edit Player Profile</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3 text-center">
                        <img src="../assets/uploads/profile_pics/<?php echo $player['profile_pic']; ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <input type="file" class="form-control mx-auto" name="profile_pic" style="max-width: 250px;" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="player_name" value="<?php echo $player['player_name']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="tel" class="form-control" name="contact" value="<?php echo $player['contact']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Playing Role</label>
                        <select class="form-select" name="player_role" required>
                            <option value="Batsman" <?php echo ($player['player_role'] == 'Batsman') ? 'selected' : ''; ?>>Batsman</option>
                            <option value="Bowler" <?php echo ($player['player_role'] == 'Bowler') ? 'selected' : ''; ?>>Bowler</option>
                            <option value="All-rounder" <?php echo ($player['player_role'] == 'All-rounder') ? 'selected' : ''; ?>>All-rounder</option>
                            <option value="Wicket-keeper" <?php echo ($player['player_role'] == 'Wicket-keeper') ? 'selected' : ''; ?>>Wicket-keeper</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Team</label>
                        <input type="text" class="form-control" value="<?php echo $player['team_name']; ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?php echo $player['email']; ?>" readonly>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Update Profile</button>
                    </div>
                </form>
                
                <hr>
                
                <div class="mt-3">
                    <h6>Change Password</h6>
                    <form action="../processes/change_password.php" method="POST">
                        <input type="hidden" name="type" value="player">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>