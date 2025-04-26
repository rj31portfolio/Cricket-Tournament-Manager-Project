<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    redirect('../player/index.php');
}

$player_id = $_SESSION['user_id'];
$sql = "SELECT p.*, t.team_name, t.logo as team_logo 
        FROM players p
        JOIN teams t ON p.team_id = t.team_id
        WHERE p.id = '$player_id'";
$player = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Get team challenges
$team_id = $player['team_id'];
$challenges_sql = "SELECT c.*, t.team_name as opponent_name 
                   FROM challenges c
                   JOIN teams t ON (c.challenger_id = t.team_id AND c.challenger_id != '$team_id') OR 
                                  (c.challenged_id = t.team_id AND c.challenged_id != '$team_id')
                   WHERE (c.challenger_id = '$team_id' OR c.challenged_id = '$team_id') AND c.status = 'Accepted'";
$challenges = mysqli_query($conn, $challenges_sql);
?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5>Player Profile</h5>
            </div>
            <div class="card-body text-center">
                <img src="../assets/uploads/profile_pics/<?php echo $player['profile_pic']; ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h4><?php echo $player['player_name']; ?></h4>
                <p class="text-muted"><?php echo $player['player_role']; ?></p>
                
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <img src="../assets/uploads/team_logos/<?php echo $player['team_logo']; ?>" alt="Team Logo" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;" class="me-2">
                    <span><?php echo $player['team_name']; ?></span>
                </div>
                
                <p><strong>Email:</strong> <?php echo $player['email']; ?></p>
                <p><strong>Contact:</strong> <?php echo $player['contact']; ?></p>
                <a href="profile.php" class="btn btn-sm btn-outline-success">Edit Profile</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5>Player Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stat-item">
                            <h3>12</h3>
                            <p class="text-muted">Matches</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <h3>450</h3>
                            <p class="text-muted">Runs</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <h3>15</h3>
                            <p class="text-muted">Wickets</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <h3>37.5</h3>
                            <p class="text-muted">Avg</p>
                        </div>
                    </div>
                </div>
                <a href="statistics.php" class="btn btn-sm btn-outline-info mt-3">View Full Stats</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Upcoming Matches</h5>
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
                                    <th>Team</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($challenge = mysqli_fetch_assoc($challenges)): ?>
                                    <tr>
                                        <td><?php echo $player['team_name']; ?> vs <?php echo $challenge['opponent_name']; ?></td>
                                        <td><?php echo $challenge['match_date']; ?></td>
                                        <td><?php echo $challenge['venue']; ?></td>
                                        <td>
                                            <span class="badge bg-success">Confirmed</span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No upcoming matches scheduled.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>