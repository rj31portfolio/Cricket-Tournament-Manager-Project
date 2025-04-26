<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$sql = "SELECT c.*, t1.team_name as challenger_name, t2.team_name as challenged_name 
        FROM challenges c
        JOIN teams t1 ON c.challenger_id = t1.team_id
        JOIN teams t2 ON c.challenged_id = t2.team_id ";
        
if ($filter == 'pending') {
    $sql .= "WHERE c.status = 'Pending'";
} elseif ($filter == 'accepted') {
    $sql .= "WHERE c.status = 'Accepted'";
} elseif ($filter == 'rejected') {
    $sql .= "WHERE c.status = 'Rejected'";
} elseif ($filter == 'completed') {
    $sql .= "WHERE c.status = 'Completed'";
}

$sql .= " ORDER BY c.match_date DESC";
$challenges = mysqli_query($conn, $sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Manage Challenges</h5>
        <div>
            <a href="?filter=all" class="btn btn-sm <?php echo ($filter == 'all') ? 'btn-primary' : 'btn-outline-primary'; ?>">All</a>
            <a href="?filter=pending" class="btn btn-sm <?php echo ($filter == 'pending') ? 'btn-warning' : 'btn-outline-warning'; ?>">Pending</a>
            <a href="?filter=accepted" class="btn btn-sm <?php echo ($filter == 'accepted') ? 'btn-success' : 'btn-outline-success'; ?>">Accepted</a>
            <a href="?filter=rejected" class="btn btn-sm <?php echo ($filter == 'rejected') ? 'btn-danger' : 'btn-outline-danger'; ?>">Rejected</a>
            <a href="?filter=completed" class="btn btn-sm <?php echo ($filter == 'completed') ? 'btn-secondary' : 'btn-outline-secondary'; ?>">Completed</a>
        </div>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($challenge = mysqli_fetch_assoc($challenges)): ?>
                        <tr>
                            <td><?php echo $challenge['challenger_name']; ?> vs <?php echo $challenge['challenged_name']; ?></td>
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
                                <div class="btn-group">
                                    <?php if ($challenge['status'] != 'Completed'): ?>
                                        <a href="process_challenge.php?complete=<?php echo $challenge['id']; ?>" class="btn btn-sm btn-secondary">Complete</a>
                                    <?php endif; ?>
                                    <a href="process_challenge.php?delete=<?php echo $challenge['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this challenge?')">Delete</a>
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