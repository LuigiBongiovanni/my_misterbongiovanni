<?php
session_start();
require_once('config/db.php');
include('includes/header.php');

if (isset($_SESSION['user'])) {
    // Se l'utente è loggato, reindirizza alla dashboard
    header('Location: dashboard/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Login
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Login riuscito
        $_SESSION['user'] = $user;
        header('Location: dashboard/index.php');
        exit;
    } else {
        // Login fallito
        $error = "Credenziali errate. Riprova.";
    }
}
?>

<div class="container mt-5">
    <h2>Login</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Accedi</button>
    </form>
    
    <p class="mt-3">Non hai un account? <a href="register.php">Registrati</a></p>
</div>

<?php include('includes/footer.php'); ?>
