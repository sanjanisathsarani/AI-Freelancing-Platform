<?php
require_once 'includes/header.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = trim($_POST['phone']);
    $extra = trim($_POST['extra']);

    try {
        if ($role === 'client') {
            $stmt = $pdo->prepare("INSERT INTO client (ClientName, Email, Password, Phone, OrganizationName) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $password, $phone, $extra]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO freelancer (Name, Email, Password, Phone, Location) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $password, $phone, $extra]);
        }
        $message = "<div class='alert-success'>Registration successful! <a href='login.php'>Login here</a>.</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert-error'>Error: Email already exists or invalid phone format.</div>";
    }
}
?>

<div class="card form-box">
    <h2>Create Account</h2>
    <?= $message ?>
    <form method="POST">
        <div class="form-group">
            <label>Register As</label>
            <select name="role" id="roleSelect" onchange="toggleRoleField()" required>
                <option value="client">Client / Organization</option>
                <option value="freelancer">AI Freelancer</option>
            </select>
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="+94771234567" required>
        </div>
        <div class="form-group">
            <label id="extraLabel">Organization Name</label>
            <input type="text" name="extra" required>
        </div>
        <button type="submit" class="btn">Register</button>
    </form>
</div>

<script>
function toggleRoleField() {
    const role = document.getElementById('roleSelect').value;
    document.getElementById('extraLabel').innerText = role === 'client' ? 'Organization Name' : 'City / Location';
}
</script>
<?php require_once 'includes/footer.php'; ?>
