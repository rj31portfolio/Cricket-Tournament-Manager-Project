<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

// Get all matches with team names and results
$sql = "SELECT c.*, t1.team_name as team1_name, t2.team_name as team2_name 
        FROM challenges c
        JOIN teams t1 ON c.challenger_id = t1.team_id
        JOIN teams t2 ON c.challenged_id = t2.team_id
        WHERE c.status = 'Completed'
        ORDER BY c.match_date DESC";
$matches = mysqli_query($conn, $sql);
?>

<div class="card">
    <div class="card-header">
        <h5>Completed Matches</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Match</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($match = mysqli_fetch_assoc($matches)): ?>
                        <tr>
                            <td><?php echo $match['team1_name']; ?> vs <?php echo $match['team2_name']; ?></td>
                            <td><?php echo $match['match_date']; ?></td>
                            <td><?php echo $match['venue']; ?></td>
                            <td>
                                <?php if ($match['result'] == 'Team1'): ?>
                                    <?php echo $match['team1_name']; ?> won
                                <?php elseif ($match['result'] == 'Team2'): ?>
                                    <?php echo $match['team2_name']; ?> won
                                <?php elseif ($match['result'] == 'Tie'): ?>
                                    Match Tied
                                <?php else: ?>
                                    No Result
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="view_match.php?id=<?php echo $match['id']; ?>" class="btn btn-sm btn-info">View</a>
                                <a href="edit_match.php?id=<?php echo $match['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>