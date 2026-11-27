<?php
require_once 'includes/header.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($role === 'client') {
        $stmt = $pdo->prepare("SELECT ClientID, ClientName, Password FROM client WHERE Email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['role'] = 'client';
            $_SESSION['user_id'] = $user['ClientID'];
            $_SESSION['user_name'] = $user['ClientName'];
            header("Location: client/dashboard.php");
            exit;
        }
    } else {
        $stmt = $pdo->prepare("SELECT FreelancerID, Name, Password FROM freelancer WHERE Email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['role'] = 'freelancer';
            $_SESSION['user_id'] = $user['FreelancerID'];
            $_SESSION['user_name'] = $user['Name'];
            header("Location: freelancer/dashboard.php");
            exit;
        }
    }
    $error = "Invalid email or password.";
}
?>

<div class="card form-box">
    <h2>Sign In</h2>
    <?php if ($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Account Type</label>
            <select name="role" required>
                <option value="client">Client</option>
                <option value="freelancer">Freelancer</option>
            </select>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
</div>
<?php require_once 'includes/footer.php'; ?>
