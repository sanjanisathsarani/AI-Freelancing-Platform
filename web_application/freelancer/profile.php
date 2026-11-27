<?php
require_once '../includes/header.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'freelancer') {
    header("Location: ../login.php");
    exit;
}

$freelancerId = $_SESSION['user_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_skill'])) {
    $skillId = $_POST['skill_id'];
    $level = $_POST['level'];
    try {
        $stmt = $pdo->prepare("INSERT INTO freelancer_skill (FreelancerID, SkillID, ProficiencyLevel) VALUES (?, ?, ?)");
        $stmt->execute([$freelancerId, $skillId, $level]);
        $msg = "<div class='alert-success'>AI Skill added to your profile!</div>";
    } catch (PDOException $e) {
        $msg = "<div class='alert-error'>You already added this skill.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $serviceId = $_POST['service_id'];
    try {
        $stmt = $pdo->prepare("INSERT INTO freelancer_service (FreelancerID, ServiceID) VALUES (?, ?)");
        $stmt->execute([$freelancerId, $serviceId]);
        $msg = "<div class='alert-success'>AI Service linked to your profile!</div>";
    } catch (PDOException $e) {
        $msg = "<div class='alert-error'>You already offer this service.</div>";
    }
}

$allSkills = $pdo->query("SELECT * FROM ai_skill")->fetchAll();
$allServices = $pdo->query("SELECT * FROM ai_service")->fetchAll();

$mySkills = $pdo->prepare("SELECT s.SkillName, fs.ProficiencyLevel FROM freelancer_skill fs JOIN ai_skill s ON fs.SkillID = s.SkillID WHERE fs.FreelancerID = ?");
$mySkills->execute([$freelancerId]);

$myServices = $pdo->prepare("SELECT srv.ServiceName, srv.Description FROM freelancer_service fs JOIN ai_service srv ON fs.ServiceID = srv.ServiceID WHERE fs.FreelancerID = ?");
$myServices->execute([$freelancerId]);
?>

<h2>Freelancer Profile: Skills & AI Services</h2>
<?= $msg ?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <div class="card">
        <h3>My AI Skills</h3>
        <ul>
            <?php foreach ($mySkills->fetchAll() as $sk): ?>
                <li style="margin: 0.4rem 0;"><?= htmlspecialchars($sk['SkillName']) ?> — <strong><?= htmlspecialchars($sk['ProficiencyLevel']) ?></strong></li>
            <?php endforeach; ?>
        </ul>
        <form method="POST" style="margin-top: 1rem;">
            <div class="form-group">
                <label>Select AI Skill</label>
                <select name="skill_id" required>
                    <?php foreach ($allSkills as $s): ?>
                        <option value="<?= $s['SkillID'] ?>"><?= htmlspecialchars($s['SkillName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Proficiency Level</label>
                <select name="level" required>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>
            <button type="submit" name="add_skill" class="btn">Add Skill</button>
        </form>
    </div>

    <div class="card">
        <h3>My Offered AI Services</h3>
        <ul>
            <?php foreach ($myServices->fetchAll() as $sv): ?>
                <li style="margin: 0.4rem 0;"><strong><?= htmlspecialchars($sv['ServiceName']) ?></strong></li>
            <?php endforeach; ?>
        </ul>
        <form method="POST" style="margin-top: 1rem;">
            <div class="form-group">
                <label>Select AI Service to Offer</label>
                <select name="service_id" required>
                    <?php foreach ($allServices as $srv): ?>
                        <option value="<?= $srv['ServiceID'] ?>"><?= htmlspecialchars($srv['ServiceName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="add_service" class="btn">Add Service</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
