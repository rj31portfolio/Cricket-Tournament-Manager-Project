<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

if (!isset($_GET['id'])) {
    redirect('teams.php');
}

// Add this sanitize function to fix the error
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

$team_id = sanitize($_GET['id']);
$sql = "SELECT * FROM teams WHERE team_id = '$team_id'";
$team = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Get team members
$members_sql = "SELECT * FROM players WHERE team_id = '$team_id'";
$members = mysqli_query($conn, $members_sql);

// Get team challenges
$challenges_sql = "SELECT c.*, t.team_name as opponent_name 
                   FROM challenges c
                   JOIN teams t ON (c.challenger_id = t.team_id AND c.challenger_id != '$team_id') OR 
                                  (c.challenged_id = t.team_id AND c.challenged_id != '$team_id')
                   WHERE (c.challenger_id = '$team_id' OR c.challenged_id = '$team_id')";
$challenges = mysqli_query($conn, $challenges_sql);
?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Team Profile</h5>
            </div>
            <div class="card-body text-center">
                <img src="../assets/uploads/team_logos/<?php echo $team['logo']; ?>" alt="Team Logo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h4><?php echo $team['team_name']; ?></h4>
                <p class="text-muted">Team ID: <?php echo $team['team_id']; ?></p>
                <p><strong>Captain:</strong> <?php echo $team['captain_name']; ?></p>
                <p><strong>Contact:</strong> <?php echo $team['contact']; ?></p>
                <p><strong>Email:</strong> <?php echo $team['email']; ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge 
                        <?php echo ($team['status'] == 'Active') ? 'bg-success' : 
                              (($team['status'] == 'Pending') ? 'bg-warning' : 'bg-danger'); ?>">
                        <?php echo $team['status']; ?>
                    </span>
                </p>
                <p><strong>Registered:</strong> <?php echo date('d M Y', strtotime($team['created_at'])); ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5>Team Members (<?php echo mysqli_num_rows($members); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (mysqli_num_rows($members) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Contact</th>
                                            <th>Email</th>
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
                                                <td><?php echo $member['email']; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>No members in this team yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5>Team Challenges</h5>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($challenges) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Match</th>
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($challenge = mysqli_fetch_assoc($challenges)): ?>
                                    <tr>
                                        <td><?php echo ($challenge['challenger_id'] == $team_id) ? "vs {$challenge['opponent_name']}" : "{$challenge['opponent_name']} vs"; ?></td>
                                        <td><?php echo $challenge['match_date']; ?></td>
                                        <td><?php echo $challenge['venue']; ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php echo ($challenge['status'] == 'Pending') ? 'bg-warning' : 
                                                      (($challenge['status'] == 'Accepted') ? 'bg-success' : 
                                                      (($challenge['status'] == 'Completed') ? 'bg-secondary' : 'bg-danger')); ?>">
                                                <?php echo $challenge['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No challenges for this team yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="teams.php" class="btn btn-outline-primary">Back to Teams</a>
</div>

<?php include '../includes/footer.php'; ?>
