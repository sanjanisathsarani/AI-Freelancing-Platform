<?php
require_once '../includes/header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'freelancer') {
    header("Location: ../login.php");
    exit;
}

$projectId = $_GET['project_id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM project WHERE ProjectID = ?");
$stmt->execute([$projectId]);
$project = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $ins = $pdo->prepare("INSERT INTO proposal (ProjectID, FreelancerID, ProposalAmount, ProposalDate, Status) VALUES (?, ?, ?, CURDATE(), 'Pending')");
    $ins->execute([$projectId, $_SESSION['user_id'], $amount]);
    header("Location: dashboard.php");
    exit;
}
?>

<div class="card form-box">
    <h2>Submit Proposal</h2>
    <p><strong>Project:</strong> <?= htmlspecialchars($project['ProjectTitle'] ?? '') ?></p>
    <p style="margin-bottom: 1rem;"><strong>Client Budget:</strong> $<?= number_format($project['Budget'] ?? 0, 2) ?></p>
    <form method="POST">
        <div class="form-group">
            <label>Your Bid Amount ($ USD)</label>
            <input type="number" step="0.01" min="10" name="amount" required>
        </div>
        <button type="submit" class="btn">Submit Proposal</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
