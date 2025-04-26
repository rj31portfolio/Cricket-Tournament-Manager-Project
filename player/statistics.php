<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    redirect('../player/index.php');
}

$player_id = $_SESSION['user_id'];
$sql = "SELECT p.*, t.team_name, ps.* 
        FROM players p
        JOIN teams t ON p.team_id = t.team_id
        LEFT JOIN player_stats ps ON p.id = ps.player_id
        WHERE p.id = '$player_id'";
$player = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Get player's match performances
$performances_sql = "SELECT ms.*, 
                    CASE 
                        WHEN c.challenger_id = t.team_id THEN c.challenged_id 
                        ELSE c.challenger_id 
                    END as opponent_id,
                    CASE 
                        WHEN c.challenger_id = t.team_id THEN ot.team_name 
                        ELSE ct.team_name 
                    END as opponent_name
                    FROM match_scorecards ms
                    JOIN challenges c ON ms.match_id = c.id
                    JOIN teams t ON p.team_id = t.team_id
                    LEFT JOIN teams ct ON c.challenger_id = ct.team_id
                    LEFT JOIN teams ot ON c.challenged_id = ot.team_id
                    WHERE c.status = 'Completed' AND (c.challenger_id = t.team_id OR c.challenged_id = t.team_id)
                    ORDER BY c.match_date DESC";
// Note: This query needs adjustment based on your player performance tracking implementation
// This is a placeholder for the actual implementation
?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5>Player Statistics</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-2 text-center">
                <img src="../assets/uploads/profile_pics/<?php echo $player['profile_pic']; ?>" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
            <div class="col-md-10">
                <h3><?php echo $player['player_name']; ?></h3>
                <p class="text-muted"><?php echo $player['player_role']; ?> | <?php echo $player['team_name']; ?></p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6>Batting Statistics</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Matches</th>
                                <td><?php echo $player['matches'] ?? 0; ?></td>
                            </tr>
                            <tr>
                                <th>Runs</th>
                                <td><?php echo $player['runs'] ?? 0; ?></td>
                            </tr>
                            <tr>
                                <th>Highest Score</th>
                                <td><?php echo $player['highest_score'] ?? 0; ?></td>
                            </tr>
                            <tr>
                                <th>Batting Average</th>
                                <td><?php echo $player['batting_avg'] ?? 0.00; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6>Bowling Statistics</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Wickets</th>
                                <td><?php echo $player['wickets'] ?? 0; ?></td>
                            </tr>
                            <tr>
                                <th>Bowling Average</th>
                                <td><?php echo $player['bowling_avg'] ?? 0.00; ?></td>
                            </tr>
                            <tr>
                                <th>Best Bowling</th>
                                <td><?php echo $player['best_bowling'] ?? 'N/A'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h6>Recent Performances</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Match</th>
                                <th>Runs</th>
                                <th>Wickets</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Team A vs Team B</td>
                                <td>45</td>
                                <td>2</td>
                                <td><span class="badge bg-success">Won</span></td>
                            </tr>
                            <tr>
                                <td>Team A vs Team C</td>
                                <td>12</td>
                                <td>0</td>
                                <td><span class="badge bg-danger">Lost</span></td>
                            </tr>
                            <tr>
                                <td>Team A vs Team D</td>
                                <td>67</td>
                                <td>3</td>
                                <td><span class="badge bg-success">Won</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>