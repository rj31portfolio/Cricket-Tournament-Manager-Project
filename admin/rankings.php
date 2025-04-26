<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

// Calculate team rankings based on wins
$rankings_sql = "SELECT t.team_id, t.team_name, t.logo, 
                COUNT(CASE WHEN c.winner_id = t.team_id THEN 1 END) as wins,
                COUNT(CASE WHEN c.loser_id = t.team_id THEN 1 END) as losses
                FROM teams t
                LEFT JOIN (
                    SELECT 
                        CASE 
                            WHEN result = 'Team1' THEN challenger_id 
                            WHEN result = 'Team2' THEN challenged_id 
                        END as winner_id,
                        CASE 
                            WHEN result = 'Team1' THEN challenged_id 
                            WHEN result = 'Team2' THEN challenger_id 
                        END as loser_id
                    FROM challenges 
                    WHERE status = 'Completed' AND result IN ('Team1', 'Team2')
                ) c ON t.team_id = c.winner_id OR t.team_id = c.loser_id
                WHERE t.status = 'Active'
                GROUP BY t.team_id
                ORDER BY wins DESC, losses ASC";
$rankings = mysqli_query($conn, $rankings_sql);
?>

<div class="card">
    <div class="card-header">
        <h5>Team Rankings</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Team</th>
                        <th>Wins</th>
                        <th>Losses</th>
                        <th>Win %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    while($team = mysqli_fetch_assoc($rankings)): 
                        $total_matches = $team['wins'] + $team['losses'];
                        $win_percentage = ($total_matches > 0) ? round(($team['wins'] / $total_matches) * 100, 2) : 0;
                    ?>
                        <tr>
                            <td><?php echo $rank++; ?></td>
                            <td>
                                <img src="../assets/uploads/team_logos/<?php echo $team['logo']; ?>" alt="Team Logo" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;" class="me-2">
                                <?php echo $team['team_name']; ?>
                            </td>
                            <td><?php echo $team['wins']; ?></td>
                            <td><?php echo $team['losses']; ?></td>
                            <td><?php echo $win_percentage; ?>%</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>