<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$sql = "SELECT * FROM teams ";
if ($filter == 'pending') {
    $sql .= "WHERE status = 'Pending'";
} elseif ($filter == 'active') {
    $sql .= "WHERE status = 'Active'";
} elseif ($filter == 'blocked') {
    $sql .= "WHERE status = 'Blocked'";
}
$sql .= " ORDER BY created_at DESC";

$teams = mysqli_query($conn, $sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Manage Teams</h5>
        <div>
            <a href="?filter=all" class="btn btn-sm <?php echo ($filter == 'all') ? 'btn-primary' : 'btn-outline-primary'; ?>">All</a>
            <a href="?filter=pending" class="btn btn-sm <?php echo ($filter == 'pending') ? 'btn-warning' : 'btn-outline-warning'; ?>">Pending</a>
            <a href="?filter=active" class="btn btn-sm <?php echo ($filter == 'active') ? 'btn-success' : 'btn-outline-success'; ?>">Active</a>
            <a href="?filter=blocked" class="btn btn-sm <?php echo ($filter == 'blocked') ? 'btn-danger' : 'btn-outline-danger'; ?>">Blocked</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Team ID</th>
                        <th>Team Name</th>
                        <th>Captain</th>
                        <th>Contact</th>
                        <th>Members</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($team = mysqli_fetch_assoc($teams)): 
                        $members_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM players WHERE team_id = '{$team['team_id']}'"))['count'];
                    ?>
                        <tr>
                            <td><?php echo $team['team_id']; ?></td>
                            <td>
                                <img src="../assets/uploads/team_logos/<?php echo $team['logo']; ?>" alt="Team Logo" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;" class="me-2">
                                <?php echo $team['team_name']; ?>
                            </td>
                            <td><?php echo $team['captain_name']; ?></td>
                            <td><?php echo $team['contact']; ?></td>
                            <td><?php echo $members_count; ?></td>
                            <td>
                                <span class="badge 
                                    <?php echo ($team['status'] == 'Active') ? 'bg-success' : 
                                          (($team['status'] == 'Pending') ? 'bg-warning' : 'bg-danger'); ?>">
                                    <?php echo $team['status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <?php if ($team['status'] == 'Pending'): ?>
                                        <a href="process_team.php?approve=<?php echo $team['team_id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                        <a href="process_team.php?reject=<?php echo $team['team_id']; ?>" class="btn btn-sm btn-danger">Reject</a>
                                    <?php elseif ($team['status'] == 'Active'): ?>
                                        <a href="process_team.php?block=<?php echo $team['team_id']; ?>" class="btn btn-sm btn-danger">Block</a>
                                    <?php else: ?>
                                        <a href="process_team.php?activate=<?php echo $team['team_id']; ?>" class="btn btn-sm btn-success">Activate</a>
                                    <?php endif; ?>
                                    <a href="view_team.php?id=<?php echo $team['team_id']; ?>" class="btn btn-sm btn-info">View</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>