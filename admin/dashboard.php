<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

// Get counts for dashboard
$teams_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM teams"))['count'];
$players_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM players"))['count'];
$active_challenges = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM challenges WHERE status = 'Accepted'"))['count'];
$pending_teams = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM teams WHERE status = 'Pending'"))['count'];
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Teams</h5>
                <h2 class="card-text"><?php echo $teams_count; ?></h2>
                <a href="teams.php" class="text-white">View all</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Players</h5>
                <h2 class="card-text"><?php echo $players_count; ?></h2>
                <a href="cricketers.php" class="text-white">View all</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Active Matches</h5>
                <h2 class="card-text"><?php echo $active_challenges; ?></h2>
                <a href="challenges.php" class="text-white">View all</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Pending Teams</h5>
                <h2 class="card-text"><?php echo $pending_teams; ?></h2>
                <a href="teams.php?filter=pending" class="text-dark">Review</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Teams</h5>
            </div>
            <div class="card-body">
                <?php
                $sql = "SELECT * FROM teams ORDER BY created_at DESC LIMIT 5";
                $result = mysqli_query($conn, $sql);
                ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Team ID</th>
                            <th>Team Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($team = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $team['team_id']; ?></td>
                                <td><?php echo $team['team_name']; ?></td>
                                <td>
                                    <span class="badge 
                                        <?php echo ($team['status'] == 'Active') ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo $team['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="teams.php" class="btn btn-sm btn-outline-primary">View All Teams</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Challenges</h5>
            </div>
            <div class="card-body">
                <?php
                $sql = "SELECT c.*, t1.team_name as challenger_name, t2.team_name as challenged_name 
                        FROM challenges c
                        JOIN teams t1 ON c.challenger_id = t1.team_id
                        JOIN teams t2 ON c.challenged_id = t2.team_id
                        ORDER BY c.created_at DESC LIMIT 5";
                $result = mysqli_query($conn, $sql);
                ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Match</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($challenge = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $challenge['challenger_name']; ?> vs <?php echo $challenge['challenged_name']; ?></td>
                                <td><?php echo $challenge['match_date']; ?></td>
                                <td>
                                    <span class="badge 
                                        <?php echo ($challenge['status'] == 'Pending') ? 'bg-warning' : 
                                              (($challenge['status'] == 'Accepted') ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo $challenge['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="challenges.php" class="btn btn-sm btn-outline-primary">View All Challenges</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>