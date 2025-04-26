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

// Get scorecard if exists
$scorecard_sql = "SELECT * FROM match_scorecards WHERE match_id = '$match_id'";
$scorecard = mysqli_query($conn, $scorecard_sql);
$has_scorecard = mysqli_num_rows($scorecard) > 0;
?>

<div class="card">
    <div class="card-header">
        <h5>Match Details</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-5 text-center">
                <img src="../assets/uploads/team_logos/<?php echo $match['team1_logo']; ?>" alt="<?php echo $match['team1_name']; ?>" style="height: 80px;">
                <h4><?php echo $match['team1_name']; ?></h4>
            </div>
            <div class="col-md-2 text-center my-auto">
                <h2>VS</h2>
            </div>
            <div class="col-md-5 text-center">
                <img src="../assets/uploads/team_logos/<?php echo $match['team2_logo']; ?>" alt="<?php echo $match['team2_name']; ?>" style="height: 80px;">
                <h4><?php echo $match['team2_name']; ?></h4>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Date:</strong> <?php echo $match['match_date']; ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Venue:</strong> <?php echo $match['venue']; ?></p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <p><strong>Status:</strong> 
                    <span class="badge 
                        <?php echo ($match['status'] == 'Completed') ? 'bg-success' : 'bg-warning'; ?>">
                        <?php echo $match['status']; ?>
                    </span>
                </p>
                
                <?php if ($match['status'] == 'Completed'): ?>
                    <p><strong>Result:</strong> 
                        <?php if ($match['result'] == 'Team1'): ?>
                            <?php echo $match['team1_name']; ?> won
                        <?php elseif ($match['result'] == 'Team2'): ?>
                            <?php echo $match['team2_name']; ?> won
                        <?php elseif ($match['result'] == 'Tie'): ?>
                            Match Tied
                        <?php else: ?>
                            No Result
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($has_scorecard): ?>
            <?php $score = mysqli_fetch_assoc($scorecard); ?>
            <div class="row">
                <div class="col-md-6">
                    <h5><?php echo $match['team1_name']; ?> Scorecard</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Runs</th>
                                <td><?php echo $score['team1_runs']; ?></td>
                            </tr>
                            <tr>
                                <th>Wickets</th>
                                <td><?php echo $score['team1_wickets']; ?></td>
                            </tr>
                            <tr>
                                <th>Overs</th>
                                <td><?php echo $score['team1_overs']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5><?php echo $match['team2_name']; ?> Scorecard</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Runs</th>
                                <td><?php echo $score['team2_runs']; ?></td>
                            </tr>
                            <tr>
                                <th>Wickets</th>
                                <td><?php echo $score['team2_wickets']; ?></td>
                            </tr>
                            <tr>
                                <th>Overs</th>
                                <td><?php echo $score['team2_overs']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <h5>Man of the Match</h5>
                <p><?php echo $score['man_of_match']; ?></p>
                
                <h5>Match Summary</h5>
                <p><?php echo $score['match_summary']; ?></p>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                No scorecard submitted for this match yet.
            </div>
            <a href="submit_scorecard.php?id=<?php echo $match_id; ?>" class="btn btn-primary">Submit Scorecard</a>
        <?php endif; ?>
    </div>
</div>

<div class="mt-3">
    <a href="matches.php" class="btn btn-outline-primary">Back to Matches</a>
</div>

<?php include '../includes/footer.php'; ?>