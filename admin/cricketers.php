<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

// Get all players with team info
$sql = "SELECT p.*, t.team_name 
        FROM players p
        JOIN teams t ON p.team_id = t.team_id
        ORDER BY p.player_name";
$players = mysqli_query($conn, $sql);
?>

<div class="card">
    <div class="card-header">
        <h5>Manage Cricketers</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Team</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($player = mysqli_fetch_assoc($players)): ?>
                        <tr>
                            <td>
                                <img src="../assets/uploads/profile_pics/<?php echo $player['profile_pic']; ?>" alt="Profile Pic" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                            </td>
                            <td><?php echo $player['player_name']; ?></td>
                            <td><?php echo $player['player_role']; ?></td>
                            <td><?php echo $player['team_name']; ?></td>
                            <td><?php echo $player['contact']; ?></td>
                            <td>
                                <a href="mailto:<?php echo $player['email']; ?>" class="btn btn-sm btn-info">Email</a>
                                <a href="edit_player.php?id=<?php echo $player['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_player.php?id=<?php echo $player['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this player?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>