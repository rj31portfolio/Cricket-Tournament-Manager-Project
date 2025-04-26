<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['team_id'])) {
    redirect('../team/index.php');
}

$team_id = $_SESSION['team_id'];

// Handle member removal
if (isset($_GET['remove'])) {
    $player_id = sanitize($_GET['remove']);
    $sql = "DELETE FROM players WHERE id = '$player_id' AND team_id = '$team_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Player removed successfully!";
    } else {
        $_SESSION['error'] = "Error removing player: " . mysqli_error($conn);
    }
    redirect('members.php');
}

// Get team members
$sql = "SELECT * FROM players WHERE team_id = '$team_id'";
$members = mysqli_query($conn, $sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Team Members</h5>
        <a href="../player/index.php" class="btn btn-primary">Add New Member</a>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($member = mysqli_fetch_assoc($members)): ?>
                        <tr>
                            <td>
                                <img src="../assets/uploads/profile_pics/<?php echo $member['profile_pic']; ?>" alt="Profile Pic" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                            </td>
                            <td><?php echo $member['player_name']; ?></td>
                            <td><?php echo $member['player_role']; ?></td>
                            <td><?php echo $member['contact']; ?></td>
                            <td>
                                <a href="mailto:<?php echo $member['email']; ?>" class="btn btn-sm btn-info">Email</a>
                                <a href="tel:<?php echo $member['contact']; ?>" class="btn btn-sm btn-success">Call</a>
                                <a href="members.php?remove=<?php echo $member['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this player?')">Remove</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>