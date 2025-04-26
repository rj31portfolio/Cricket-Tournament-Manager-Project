<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['team_id'])) {
    redirect('../team/index.php');
}

$team_id = $_SESSION['team_id'];

// Get all challenges for the team
$sql = "SELECT c.*, t1.team_name as challenger_name, t2.team_name as challenged_name 
        FROM challenges c
        JOIN teams t1 ON c.challenger_id = t1.team_id
        JOIN teams t2 ON c.challenged_id = t2.team_id
        WHERE c.challenger_id = '$team_id' OR c.challenged_id = '$team_id'
        ORDER BY c.match_date DESC";
$challenges = mysqli_query($conn, $sql);
?>

<div class="card">
    <div class="card-header">
        <h5>Team Challenges</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Match</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($challenge = mysqli_fetch_assoc($challenges)): ?>
                        <tr>
                            <td>
                                <?php if ($challenge['challenger_id'] == $team_id): ?>
                                    You vs <?php echo $challenge['challenged_name']; ?>
                                <?php else: ?>
                                    <?php echo $challenge['challenger_name']; ?> vs You
                                <?php endif; ?>
                            </td>
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
                            <td>
                                <?php if ($challenge['status'] == 'Completed'): ?>
                                    <?php if ($challenge['result'] == 'Team1'): ?>
                                        <?php echo ($challenge['challenger_id'] == $team_id) ? 'You won' : 'You lost'; ?>
                                    <?php elseif ($challenge['result'] == 'Team2'): ?>
                                        <?php echo ($challenge['challenged_id'] == $team_id) ? 'You won' : 'You lost'; ?>
                                    <?php elseif ($challenge['result'] == 'Tie'): ?>
                                        Match Tied
                                    <?php else: ?>
                                        No Result
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>