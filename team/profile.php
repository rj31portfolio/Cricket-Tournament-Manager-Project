<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['team_id'])) {
    redirect('../team/index.php');
}

// Define the sanitize function
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$team_id = $_SESSION['team_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_name = sanitize($_POST['team_name']);
    $captain_name = sanitize($_POST['captain_name']);
    $contact = sanitize($_POST['contact']);
    $email = sanitize($_POST['email']);
    
    // Handle file upload
    if (!empty($_FILES['logo']['name'])) {
        $target_dir = "../assets/uploads/team_logos/";
        $file_ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $new_filename = $team_id . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES['logo']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
                $logo_path = $new_filename;
                $sql = "UPDATE teams SET 
                        team_name = '$team_name', 
                        captain_name = '$captain_name', 
                        contact = '$contact', 
                        email = '$email', 
                        logo = '$logo_path' 
                        WHERE team_id = '$team_id'";
            }
        }
    } else {
        $sql = "UPDATE teams SET 
                team_name = '$team_name', 
                captain_name = '$captain_name', 
                contact = '$contact', 
                email = '$email' 
                WHERE team_id = '$team_id'";
    }
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Profile updated successfully!";
        $_SESSION['team_name'] = $team_name;
        redirect('profile.php');
    } else {
        $_SESSION['error'] = "Error updating profile: " . mysqli_error($conn);
    }
}

// Get team data
$sql = "SELECT * FROM teams WHERE team_id = '$team_id'";
$team = mysqli_fetch_assoc(mysqli_query($conn, $sql));
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Edit Team Profile</h5>
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
                        <img src="../assets/uploads/team_logos/<?php echo $team['logo']; ?>" alt="Team Logo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <input type="file" class="form-control mx-auto" name="logo" style="max-width: 250px;" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Team Name</label>
                        <input type="text" class="form-control" name="team_name" value="<?php echo htmlspecialchars($team['team_name']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Captain Name</label>
                        <input type="text" class="form-control" name="captain_name" value="<?php echo htmlspecialchars($team['captain_name']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="tel" class="form-control" name="contact" value="<?php echo htmlspecialchars($team['contact']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($team['email']); ?>" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
                
                <hr>
                
                <div class="mt-3">
                    <h6>Change Password</h6>
                    <form action="../processes/change_password.php" method="POST">
                        <input type="hidden" name="type" value="team">
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
