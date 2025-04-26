<?php
include '../includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/index.php');
}

// Get all active teams
$teams_sql = "SELECT team_id, team_name FROM teams WHERE status = 'Active'";
$teams = mysqli_query($conn, $teams_sql);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Tournament Brackets</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateBracketModal">Generate Brackets</button>
    </div>
    <div class="card-body">
        <div class="tournament-brackets">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4>Quarter Finals</h4>
                    <div class="d-flex justify-content-around mb-4">
                        <div class="matchup">
                            <div class="team">Team A</div>
                            <div class="vs">vs</div>
                            <div class="team">Team B</div>
                        </div>
                        <div class="matchup">
                            <div class="team">Team C</div>
                            <div class="vs">vs</div>
                            <div class="team">Team D</div>
                        </div>
                        <div class="matchup">
                            <div class="team">Team E</div>
                            <div class="vs">vs</div>
                            <div class="team">Team F</div>
                        </div>
                        <div class="matchup">
                            <div class="team">Team G</div>
                            <div class="vs">vs</div>
                            <div class="team">Team H</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4>Semi Finals</h4>
                    <div class="d-flex justify-content-center mb-4">
                        <div class="matchup mx-5">
                            <div class="team">Winner QF1</div>
                            <div class="vs">vs</div>
                            <div class="team">Winner QF2</div>
                        </div>
                        <div class="matchup mx-5">
                            <div class="team">Winner QF3</div>
                            <div class="vs">vs</div>
                            <div class="team">Winner QF4</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4>Final</h4>
                    <div class="d-flex justify-content-center">
                        <div class="matchup">
                            <div class="team">Winner SF1</div>
                            <div class="vs">vs</div>
                            <div class="team">Winner SF2</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Bracket Modal -->
<div class="modal fade" id="generateBracketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Tournament Brackets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="generateBracketForm">
                    <div class="mb-3">
                        <label class="form-label">Select Teams (Select 4, 8, or 16 teams)</label>
                        <select class="form-select" multiple size="8" id="selectedTeams" required>
                            <?php while($team = mysqli_fetch_assoc($teams)): ?>
                                <option value="<?php echo $team['team_id']; ?>"><?php echo $team['team_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tournament Name</label>
                        <input type="text" class="form-control" id="tournamentName" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="generateBracketBtn">Generate</button>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>