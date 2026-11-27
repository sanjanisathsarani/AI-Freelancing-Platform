<?php
require_once '../includes/header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

$clientId = $_SESSION['user_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    $assignmentId = $_POST['assignment_id'];
    $amount = $_POST['amount'];
    $stmt = $pdo->prepare("INSERT INTO payment (AssignmentID, Amount, PaymentDate, PaymentStatus) VALUES (?, ?, CURDATE(), 'Completed')");
    $stmt->execute([$assignmentId, $amount]);
    $msg = "<div class='alert-success'>Payment recorded successfully!</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    $assignmentId = $_POST['assignment_id'];
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);
    $stmt = $pdo->prepare("INSERT INTO review (AssignmentID, Rating, Comment, ReviewType, ReviewDate) VALUES (?, ?, ?, 'Client to Freelancer', CURDATE())");
    $stmt->execute([$assignmentId, $rating, $comment]);
    $msg = "<div class='alert-success'>Review submitted successfully!</div>";
}

$stmt = $pdo->prepare("
    SELECT p.*, COUNT(pr.ProposalID) AS ProposalCount,
           a.AssignmentID, f.Name AS AssignedFreelancer
    FROM project p
    LEFT JOIN proposal pr ON p.ProjectID = pr.ProjectID
    LEFT JOIN assignment a ON pr.ProposalID = a.ProposalID AND pr.Status = 'Accepted'
    LEFT JOIN freelancer f ON pr.FreelancerID = f.FreelancerID
    WHERE p.ClientID = ?
    GROUP BY p.ProjectID
    ORDER BY p.CreatedAt DESC
");
$stmt->execute([$clientId]);
$myProjects = $stmt->fetchAll();
?>

<div class="card" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2>Client Dashboard</h2>
        <p>Welcome back, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
    </div>
    <a href="post_project.php" class="btn">+ Post New AI Project</a>
</div>

<?= $msg ?>

<div class="card">
    <h3>My Posted AI Projects</h3>
    <table>
        <thead>
            <tr>
                <th>Project Title</th>
                <th>Budget</th>
                <th>Status</th>
                <th>Proposals</th>
                <th>Assigned Freelancer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($myProjects as $proj): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($proj['ProjectTitle']) ?></strong></td>
                    <td>$<?= number_format($proj['Budget'], 2) ?></td>
                    <td><?= htmlspecialchars($proj['Status']) ?></td>
                    <td><?= $proj['ProposalCount'] ?></td>
                    <td><?= htmlspecialchars($proj['AssignedFreelancer'] ?: 'Not Assigned') ?></td>
                    <td>
                        <a href="view_proposals.php?project_id=<?= $proj['ProjectID'] ?>" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">View Proposals</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php foreach ($myProjects as $proj): ?>
    <?php if ($proj['AssignmentID']): ?>
        <div class="card">
            <h4>Manage Active Assignment: <?= htmlspecialchars($proj['ProjectTitle']) ?> (Freelancer: <?= htmlspecialchars($proj['AssignedFreelancer']) ?>)</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
                <form method="POST">
                    <input type="hidden" name="assignment_id" value="<?= $proj['AssignmentID'] ?>">
                    <div class="form-group">
                        <label>Record Payment ($)</label>
                        <input type="number" step="0.01" name="amount" required>
                    </div>
                    <button type="submit" name="add_payment" class="btn">Make Payment</button>
                </form>

                <form method="POST">
                    <input type="hidden" name="assignment_id" value="<?= $proj['AssignmentID'] ?>">
                    <div class="form-group">
                        <label>Rate Freelancer (1–5)</label>
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
                    <button type="submit" name="add_review" class="btn">Submit Review</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php require_once '../includes/footer.php'; ?>
