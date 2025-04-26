<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['team_id'])) {
    redirect('../team/index.php');
}

$team_id = $_SESSION['team_id'];
$sql = "SELECT * FROM teams WHERE team_id = '$team_id'";
$team = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Get team members
$members_sql = "SELECT * FROM players WHERE team_id = '$team_id'";
$members = mysqli_query($conn, $members_sql);

// Get challenges
$challenges_sql = "SELECT * FROM challenges WHERE (challenger_id = '$team_id' OR challenged_id = '$team_id') AND status != 'Completed'";
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
                <p><strong>Status:</strong> <span class="badge bg-success"><?php echo $team['status']; ?></span></p>
                <a href="profile.php" class="btn btn-sm btn-outline-primary">Edit Profile</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5>Team Members (<?php echo mysqli_num_rows($members); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (mysqli_num_rows($members) > 0): ?>
                            <ul class="list-group">
                                <?php while($member = mysqli_fetch_assoc($members)): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo $member['player_name']; ?>
                                        <span class="badge bg-primary rounded-pill"><?php echo $member['player_role']; ?></span>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p>No members added yet.</p>
                        <?php endif; ?>
                        <a href="members.php" class="btn btn-sm btn-outline-info mt-3">Manage Members</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5>Send Challenge</h5>
                    </div>
                    <div class="card-body">
                        <form action="../processes/challenge_process.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Select Team</label>
                                <select class="form-select" name="challenged_id" required>
                                    <option value="">-- Select Team --</option>
                                    <?php
                                    $teams_sql = "SELECT team_id, team_name FROM teams WHERE team_id != '$team_id' AND status = 'Active'";
                                    $teams = mysqli_query($conn, $teams_sql);
                                    while($team = mysqli_fetch_assoc($teams)) {
                                        echo "<option value='{$team['team_id']}'>{$team['team_name']} ({$team['team_id']})</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Proposed Date</label>
                                <input type="date" class="form-control" name="match_date" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Venue</label>
                                <input type="text" class="form-control" name="venue" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Send Challenge</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5>Challenges</h5>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($challenge = mysqli_fetch_assoc($challenges)): 
                                    $opponent_id = ($challenge['challenger_id'] == $team_id) ? $challenge['challenged_id'] : $challenge['challenger_id'];
                                    $opponent_sql = "SELECT team_name FROM teams WHERE team_id = '$opponent_id'";
                                    $opponent = mysqli_fetch_assoc(mysqli_query($conn, $opponent_sql));
                                ?>
                                    <tr>
                                        <td><?php echo ($challenge['challenger_id'] == $team_id) ? "You vs {$opponent['team_name']}" : "{$opponent['team_name']} vs You"; ?></td>
                                        <td><?php echo $challenge['match_date']; ?></td>
                                        <td><?php echo $challenge['venue']; ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php echo ($challenge['status'] == 'Pending') ? 'bg-warning' : 
                                                      (($challenge['status'] == 'Accepted') ? 'bg-success' : 'bg-danger'); ?>">
                                                <?php echo $challenge['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($challenge['challenged_id'] == $team_id && $challenge['status'] == 'Pending'): ?>
                                                <a href="../processes/challenge_process.php?accept=<?php echo $challenge['id']; ?>" class="btn btn-sm btn-success">Accept</a>
                                                <a href="../processes/challenge_process.php?reject=<?php echo $challenge['id']; ?>" class="btn btn-sm btn-danger">Reject</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No active challenges.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>