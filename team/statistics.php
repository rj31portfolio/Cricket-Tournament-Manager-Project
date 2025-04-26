<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['team_id'])) {
    redirect('../team/index.php');
}

$team_id = $_SESSION['team_id'];

// Get team details
$team_sql = "SELECT * FROM teams WHERE team_id = '$team_id'";
$team = mysqli_fetch_assoc(mysqli_query($conn, $team_sql));

// Get team players with their statistics
$players_sql = "SELECT p.*, ps.* 
                FROM players p
                LEFT JOIN player_stats ps ON p.id = ps.player_id
                WHERE p.team_id = '$team_id'
                ORDER BY p.player_name";
$players = mysqli_query($conn, $players_sql);

// Get team matches
$matches_sql = "SELECT c.*, 
                t1.team_name as team1_name, t1.logo as team1_logo,
                t2.team_name as team2_name, t2.logo as team2_logo,
                ms.result, ms.man_of_match
                FROM challenges c
                JOIN teams t1 ON c.challenger_id = t1.team_id
                JOIN teams t2 ON c.challenged_id = t2.team_id
                LEFT JOIN match_scorecards ms ON c.id = ms.match_id
                WHERE (c.challenger_id = '$team_id' OR c.challenged_id = '$team_id')
                AND c.status = 'Completed'
                ORDER BY c.match_date DESC";
$matches = mysqli_query($conn, $matches_sql);

// Calculate team statistics
$team_stats_sql = "SELECT 
                  COUNT(*) as total_matches,
                  SUM(CASE WHEN (c.challenger_id = '$team_id' AND ms.result = 'Team1') OR 
                               (c.challenged_id = '$team_id' AND ms.result = 'Team2') THEN 1 ELSE 0 END) as wins,
                  SUM(CASE WHEN (c.challenger_id = '$team_id' AND ms.result = 'Team2') OR 
                               (c.challenged_id = '$team_id' AND ms.result = 'Team1') THEN 1 ELSE 0 END) as losses,
                  SUM(CASE WHEN ms.result = 'Tie' THEN 1 ELSE 0 END) as ties,
                  SUM(CASE WHEN ms.result = 'NoResult' THEN 1 ELSE 0 END) as no_results
                  FROM challenges c
                  LEFT JOIN match_scorecards ms ON c.id = ms.match_id
                  WHERE (c.challenger_id = '$team_id' OR c.challenged_id = '$team_id')
                  AND c.status = 'Completed'";
$team_stats = mysqli_fetch_assoc(mysqli_query($conn, $team_stats_sql));
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5>Team Statistics - <?php echo $team['team_name']; ?></h5>
    </div>
    <div class="card-body">
        <!-- Team Summary -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="team-summary text-center">
                    <img src="../assets/uploads/team_logos/<?php echo $team['logo']; ?>" alt="Team Logo" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <h4><?php echo $team['team_name']; ?></h4>
                    <div class="d-flex justify-content-center mt-3">
                        <div class="stat-box mx-3">
                            <h3><?php echo $team_stats['total_matches']; ?></h3>
                            <p class="text-muted">Matches</p>
                        </div>
                        <div class="stat-box mx-3">
                            <h3 class="text-success"><?php echo $team_stats['wins']; ?></h3>
                            <p class="text-muted">Wins</p>
                        </div>
                        <div class="stat-box mx-3">
                            <h3 class="text-danger"><?php echo $team_stats['losses']; ?></h3>
                            <p class="text-muted">Losses</p>
                        </div>
                        <div class="stat-box mx-3">
                            <h3 class="text-warning"><?php echo $team_stats['ties']; ?></h3>
                            <p class="text-muted">Ties</p>
                        </div>
                    </div>
                    <?php if ($team_stats['total_matches'] > 0): ?>
                        <div class="win-rate mt-3">
                            <h5>Win Rate: <?php echo round(($team_stats['wins'] / $team_stats['total_matches']) * 100, 2); ?>%</h5>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Player Statistics -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h5>Player Statistics</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Player</th>
                                <th>Role</th>
                                <th>Matches</th>
                                <th>Runs</th>
                                <th>Wickets</th>
                                <th>Bat Avg</th>
                                <th>Bowl Avg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($player = mysqli_fetch_assoc($players)): ?>
                                <tr>
                                    <td>
                                        <img src="../assets/uploads/profile_pics/<?php echo $player['profile_pic']; ?>" alt="Player" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                        <?php echo $player['player_name']; ?>
                                    </td>
                                    <td><?php echo $player['player_role']; ?></td>
                                    <td><?php echo $player['matches'] ?? 0; ?></td>
                                    <td><?php echo $player['runs'] ?? 0; ?></td>
                                    <td><?php echo $player['wickets'] ?? 0; ?></td>
                                    <td><?php echo $player['batting_avg'] ?? '-'; ?></td>
                                    <td><?php echo $player['bowling_avg'] ?? '-'; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Match History -->
        <div class="row">
            <div class="col-md-12">
                <h5>Match History</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Match</th>
                                <th>Date</th>
                                <th>Result</th>
                                <th>Man of Match</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($match = mysqli_fetch_assoc($matches)): ?>
                                <tr>
                                    <td>
                                        <?php if ($match['challenger_id'] == $team_id): ?>
                                            <strong>You</strong> vs <?php echo $match['team2_name']; ?>
                                        <?php else: ?>
                                            <?php echo $match['team1_name']; ?> vs <strong>You</strong>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($match['match_date'])); ?></td>
                                    <td>
                                        <?php if ($match['result'] == 'Team1'): ?>
                                            <?php echo ($match['challenger_id'] == $team_id) ? '<span class="badge bg-success">Won</span>' : '<span class="badge bg-danger">Lost</span>'; ?>
                                        <?php elseif ($match['result'] == 'Team2'): ?>
                                            <?php echo ($match['challenged_id'] == $team_id) ? '<span class="badge bg-success">Won</span>' : '<span class="badge bg-danger">Lost</span>'; ?>
                                        <?php elseif ($match['result'] == 'Tie'): ?>
                                            <span class="badge bg-warning">Tied</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No Result</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $match['man_of_match'] ?? '-'; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>