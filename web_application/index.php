<?php
require_once 'includes/header.php';

$projectsStmt = $pdo->query("
    SELECT p.*, c.ClientName, c.OrganizationName,
           GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS RequiredSkills
    FROM project p
    JOIN client c ON p.ClientID = c.ClientID
    LEFT JOIN project_requirement pr ON p.ProjectID = pr.ProjectID
    LEFT JOIN ai_skill s ON pr.SkillID = s.SkillID
    GROUP BY p.ProjectID
    ORDER BY p.CreatedAt DESC
");
$projects = $projectsStmt->fetchAll();

$servicesStmt = $pdo->query("
    SELECT srv.ServiceName, srv.Description, f.Name AS FreelancerName, f.Location
    FROM ai_service srv
    JOIN freelancer_service fs ON srv.ServiceID = fs.ServiceID
    JOIN freelancer f ON fs.FreelancerID = f.FreelancerID
");
$services = $servicesStmt->fetchAll();
?>

<div class="card">
    <h1>AI Projects & Services Freelancing Platform</h1>
    <p>Connect with top AI engineers across Machine Learning, Deep Learning, NLP, Computer Vision, and Generative AI.</p>
    <div style="margin-top: 1rem;">
        <input type="text" id="searchInput" onkeyup="filterProjects()" placeholder="Search projects by title or AI skill..." style="width: 100%; padding: 0.7rem; border: 1px solid #cbd5e1; border-radius: 5px;">
    </div>
</div>

<h2>Available AI Projects</h2>
<div id="projectList">
    <?php foreach ($projects as $p): ?>
        <div class="card project-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3><?= htmlspecialchars($p['ProjectTitle']) ?></h3>
                <span style="background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 600;">
                    <?= htmlspecialchars($p['Status']) ?>
                </span>
            </div>
            <p style="color: #64748b; font-size: 0.9rem; margin: 0.3rem 0;">
                Client: <strong><?= htmlspecialchars($p['OrganizationName'] ?: $p['ClientName']) ?></strong> | Budget: <strong>$<?= number_format($p['Budget'], 2) ?></strong>
            </p>
            <p><?= htmlspecialchars($p['Description']) ?></p>
            <p style="margin-top: 0.5rem;"><strong>Required AI Skills:</strong> <?= htmlspecialchars($p['RequiredSkills'] ?: 'General AI') ?></p>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'freelancer' && $p['Status'] === 'Open'): ?>
                <a href="freelancer/submit_proposal.php?project_id=<?= $p['ProjectID'] ?>" class="btn" style="margin-top: 0.8rem;">Submit Proposal</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<h2 style="margin-top: 2rem;">Featured AI Services</h2>
<div class="card">
    <table>
        <thead>
            <tr>
                <th>AI Service</th>
                <th>Description</th>
                <th>Freelancer</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $srv): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($srv['ServiceName']) ?></strong></td>
                    <td><?= htmlspecialchars($srv['Description']) ?></td>
                    <td><?= htmlspecialchars($srv['FreelancerName']) ?></td>
                    <td><?= htmlspecialchars($srv['Location']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
