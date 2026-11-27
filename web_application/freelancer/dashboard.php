<?php
require_once '../includes/header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'freelancer') {
    header("Location: ../login.php");
    exit;
}

$freelancerId = $_SESSION['user_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_assignment'])) {
    $assignmentId = $_POST['assignment_id'];
    $projectId = $_POST['project_id'];
    $pdo->prepare("UPDATE assignment SET Status = 'Completed', EndDate = CURDATE() WHERE AssignmentID = ?")->execute([$assignmentId]);
    $pdo->prepare("UPDATE project SET Status = 'Completed' WHERE ProjectID = ?")->execute([$projectId]);
    $msg = "<div class='alert-success'>Project marked as Completed!</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rate_client'])) {
    $assignmentId = $_POST['assignment_id'];
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);
    $stmt = $pdo->prepare("INSERT INTO review (AssignmentID, Rating, Comment, ReviewType, ReviewDate) VALUES (?, ?, ?, 'Freelancer to Client', CURDATE())");
    $stmt->execute([$assignmentId, $rating, $comment]);
    $msg = "<div class='alert-success'>Review submitted for the client!</div>";
}

$stmt = $pdo->prepare("
    SELECT pr.*, p.ProjectID, p.ProjectTitle, p.Budget, c.ClientName, c.OrganizationName,
           a.AssignmentID, a.Status AS AssignmentStatus, a.StartDate,
           COALESCE(SUM(pay.Amount), 0) AS TotalPaid
    FROM proposal pr
    JOIN project p ON pr.ProjectID = p.ProjectID
    JOIN client c ON p.ClientID = c.ClientID
    LEFT JOIN assignment a ON pr.ProposalID = a.ProposalID
    LEFT JOIN payment pay ON a.AssignmentID = pay.AssignmentID AND pay.PaymentStatus = 'Completed'
    WHERE pr.FreelancerID = ?
    GROUP BY pr.ProposalID
    ORDER BY pr.ProposalDate DESC
");
$stmt->execute([$freelancerId]);
$myProposals = $stmt->fetchAll();
?>

<div class="card" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2>Freelancer Dashboard</h2>
        <p>Welcome back, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
    </div>
    <div>
        <a href="profile.php" class="btn">Manage Skills & Services</a>
        <a href="../index.php" class="btn" style="background: #0f766e; margin-left: 0.5rem;">Find Projects</a>
    </div>
</div>

<?= $msg ?>

<div class="card">
    <h3>My Proposals & Active Projects</h3>
    <table>
        <thead>
            <tr>
                <th>Project</th>
                <th>Client</th>
                <th>My Bid</th>
                <th>Proposal Status</th>
                <th>Assignment Status</th>
                <th>Paid Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($myProposals as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['ProjectTitle']) ?></strong></td>
                    <td><?= htmlspecialchars($row['OrganizationName'] ?: $row['ClientName']) ?></td>
                    <td>$<?= number_format($row['ProposalAmount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['Status']) ?></td>
                    <td><?= htmlspecialchars($row['AssignmentStatus'] ?: '—') ?></td>
                    <td>$<?= number_format($row['TotalPaid'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php foreach ($myProposals as $row): ?>
    <?php if ($row['AssignmentID']): ?>
        <div class="card">
            <h4>Assignment: <?= htmlspecialchars($row['ProjectTitle']) ?> (Status: <?= htmlspecialchars($row['AssignmentStatus']) ?>)</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
                <?php if ($row['AssignmentStatus'] === 'Active'): ?>
                    <form method="POST">
                        <input type="hidden" name="assignment_id" value="<?= $row['AssignmentID'] ?>">
                        <input type="hidden" name="project_id" value="<?= $row['ProjectID'] ?>">
                        <p style="margin-bottom: 0.8rem;">Finished all deliverables for this AI project?</p>
                        <button type="submit" name="complete_assignment" class="btn">Mark Project Completed</button>
                    </form>
                <?php else: ?>
                    <p><strong>Project Completed!</strong></p>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="assignment_id" value="<?= $row['AssignmentID'] ?>">
                    <div class="form-group">
                        <label>Rate Client (1–5)</label>
                        <select name="rating" required>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Below Average</option>
                            <option value="1">1 - Poor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Review Comment</label>
                        <input type="text" name="comment" required>
                    </div>
                    <button type="submit" name="rate_client" class="btn">Submit Client Review</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php require_once '../includes/footer.php'; ?>
