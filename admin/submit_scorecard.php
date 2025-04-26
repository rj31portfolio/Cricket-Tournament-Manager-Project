<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

if (!isset($_GET['id'])) {
    redirect('matches.php');
}

$match_id = sanitize($_GET['id']);
$sql = "SELECT c.*, t1.team_name as team1_name, t1.logo as team1_logo, 
               t2.team_name as team2_name, t2.logo as team2_logo
        FROM challenges c
        JOIN teams t1 ON c.challenger_id = t1.team_id
        JOIN teams t2 ON c.challenged_id = t2.team_id
        WHERE c.id = '$match_id'";
$match = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Get players from both teams
$team1_players_sql = "SELECT id, player_name FROM players WHERE team_id = '{$match['challenger_id']}'";
$team1_players = mysqli_query($conn, $team1_players_sql);

$team2_players_sql = "SELECT id, player_name FROM players WHERE team_id = '{$match['challenged_id']}'";
$team2_players = mysqli_query($conn, $team2_players_sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team1_runs = sanitize($_POST['team1_runs']);
    $team1_wickets = sanitize($_POST['team1_wickets']);
    $team1_overs = sanitize($_POST['team1_overs']);
    
    $team2_runs = sanitize($_POST['team2_runs']);
    $team2_wickets = sanitize($_POST['team2_wickets']);
    $team2_overs = sanitize($_POST['team2_overs']);
    
    $result = sanitize($_POST['result']);
    $man_of_match = sanitize($_POST['man_of_match']);
    $match_summary = sanitize($_POST['match_summary']);
    
    // Insert scorecard
    $sql = "INSERT INTO match_scorecards 
            (match_id, team1_runs, team1_wickets, team1_overs, 
             team2_runs, team2_wickets, team2_overs, 
             result, man_of_match, match_summary)
            VALUES 
            ('$match_id', '$team1_runs', '$team1_wickets', '$team1_overs',
             '$team2_runs', '$team2_wickets', '$team2_overs',
             '$result', '$man_of_match', '$match_summary')";
    
    if (mysqli_query($conn, $sql)) {
        // Update match result
        $update_sql = "UPDATE challenges SET result = '$result' WHERE id = '$match_id'";
        mysqli_query($conn, $update_sql);
        
        $_SESSION['success'] = "Scorecard submitted successfully!";
        redirect('view_match.php?id=' . $match_id);
    } else {
        $_SESSION['error'] = "Error submitting scorecard: " . mysqli_error($conn);
    }
}
?>

<div class="card">
    <div class="card-header">
        <h5>Submit Scorecard: <?php echo $match['team1_name']; ?> vs <?php echo $match['team2_name']; ?></h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5><?php echo $match['team1_name']; ?></h5>
                    <div class="mb-3">
                        <label class="form-label">Runs</label>
                        <input type="number" class="form-control" name="team1_runs" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wickets</label>
                        <input type="number" class="form-control" name="team1_wickets" min="0" max="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Overs</label>
                        <input type="number" class="form-control" name="team1_overs" step="0.1" min="0" max="50" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h5><?php echo $match['team2_name']; ?></h5>
                    <div class="mb-3">
                        <label class="form-label">Runs</label>
                        <input type="number" class="form-control" name="team2_runs" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wickets</label>
                        <input type="number" class="form-control" name="team2_wickets" min="0" max="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Overs</label>
                        <input type="number" class="form-control" name="team2_overs" step="0.1" min="0" max="50" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Match Result</label>
                <select class="form-select" name="result" required>
                    <option value="">-- Select Result --</option>
                    <option value="Team1"><?php echo $match['team1_name']; ?> Won</option>
                    <option value="Team2"><?php echo $match['team2_name']; ?> Won</option>
                    <option value="Tie">Match Tied</option>
                    <option value="NoResult">No Result</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Man of the Match</label>
                <select class="form-select" name="man_of_match" required>
                    <option value="">-- Select Player --</option>
                    <optgroup label="<?php echo $match['team1_name']; ?>">
                        <?php while($player = mysqli_fetch_assoc($team1_players)): ?>
                            <option value="<?php echo $player['player_name']; ?>"><?php echo $player['player_name']; ?></option>
                        <?php endwhile; ?>
                    </optgroup>
                    <optgroup label="<?php echo $match['team2_name']; ?>">
                        <?php while($player = mysqli_fetch_assoc($team2_players)): ?>
                            <option value="<?php echo $player['player_name']; ?>"><?php echo $player['player_name']; ?></option>
                        <?php endwhile; ?>
                    </optgroup>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Match Summary</label>
                <textarea class="form-control" name="match_summary" rows="3" required></textarea>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit Scorecard</button>
            </div>
        </form>
    </div>
</div>

<div class="mt-3">
    <a href="matches.php" class="btn btn-outline-secondary">Back to Matches</a>
</div>

<?php include '../includes/footer.php'; ?>