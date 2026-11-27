<?php
require_once '../includes/header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

$skills = $pdo->query("SELECT * FROM ai_skill")->fetchAll();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $budget = $_POST['budget'];
    $skillId = $_POST['skill_id'];
    $reqDetails = trim($_POST['req_details']);

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO project (ClientID, ProjectTitle, Description, Budget, Status) VALUES (?, ?, ?, ?, 'Open')");
        $stmt->execute([$_SESSION['user_id'], $title, $description, $budget]);
        $projectId = $pdo->lastInsertId();

        if (!empty($skillId)) {
            $reqStmt = $pdo->prepare("INSERT INTO project_requirement (ProjectID, SkillID, RequirementDetails) VALUES (?, ?, ?)");
            $reqStmt->execute([$projectId, $skillId, $reqDetails ?: 'Standard proficiency required']);
        }
        $pdo->commit();
        header("Location: dashboard.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "<div class='alert-error'>Failed to post project: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="card form-box">
    <h2>Post a New AI Project</h2>
    <?= $msg ?>
    <form method="POST">
        <div class="form-group">
            <label>Project Title</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Project Description</label>
            <textarea name="description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label>Budget ($ USD)</label>
            <input type="number" step="0.01" min="10" name="budget" required>
        </div>
        <div class="form-group">
            <label>Primary Required AI Skill</label>
            <select name="skill_id" required>
                <?php foreach ($skills as $s): ?>
                    <option value="<?= $s['SkillID'] ?>"><?= htmlspecialchars($s['SkillName']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Technical Requirement Details</label>
            <input type="text" name="req_details" placeholder="e.g., Accuracy > 90% with REST API">
        </div>
        <button type="submit" class="btn">Publish Project</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
