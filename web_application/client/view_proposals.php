<?php
require_once '../includes/header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

$projectId = $_GET['project_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_proposal'])) {
    $proposalId = $_POST['proposal_id'];
    $pdo->beginTransaction();
    try {
        $pdo->prepare("UPDATE proposal SET Status = 'Accepted' WHERE ProposalID = ?")->execute([$proposalId]);
        $pdo->prepare("UPDATE proposal SET Status = 'Rejected' WHERE ProjectID = ? AND ProposalID != ?")->execute([$projectId, $proposalId]);
        $pdo->prepare("INSERT INTO assignment (ProposalID, StartDate, Status) VALUES (?, CURDATE(), 'Active')")->execute([$proposalId]);
        $pdo->prepare("UPDATE project SET Status = 'In Progress' WHERE ProjectID = ?")->execute([$projectId]);
        $pdo->commit();
        header("Location: dashboard.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

$projStmt = $pdo->prepare("SELECT * FROM project WHERE ProjectID = ? AND ClientID = ?");
$projStmt->execute([$projectId, $_SESSION['user_id']]);
$project = $projStmt->fetch();

$propStmt = $pdo->prepare("
    SELECT pr.*, f.Name, f.Email, f.Location
    FROM proposal pr
    JOIN freelancer f ON pr.FreelancerID = f.FreelancerID
    WHERE pr.ProjectID = ?
");
$propStmt->execute([$projectId]);
$proposals = $propStmt->fetchAll();
?>

<div class="card">
    <h2>Proposals for: <?= htmlspecialchars($project['ProjectTitle'] ?? 'Project') ?></h2>
    <table>
        <thead>
            <tr>
                <th>Freelancer</th>
                <th>Location</th>
                <th>Bid Amount</th>
                <th>Date Submitted</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proposals as $prop): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($prop['Name']) ?></strong><br><small><?= htmlspecialchars($prop['Email']) ?></small></td>
                    <td><?= htmlspecialchars($prop['Location']) ?></td>
                    <td>$<?= number_format($prop['ProposalAmount'], 2) ?></td>
                    <td><?= htmlspecialchars($prop['ProposalDate']) ?></td>
                    <td><?= htmlspecialchars($prop['Status']) ?></td>
                    <td>
                        <?php if ($prop['Status'] === 'Pending' && $project['Status'] === 'Open'): ?>
                            <form method="POST" onsubmit="return confirm('Assign this freelancer to the project?');">
                                <input type="hidden" name="proposal_id" value="<?= $prop['ProposalID'] ?>">
                                <button type="submit" name="accept_proposal" class="btn">Accept & Assign</button>
                            </form>
                        <?php else: ?>
                            <span>—</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>
