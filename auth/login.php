p<?php
session_start();
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $_SESSION['user'] = $user;
        header("Location: ../dashboard.php");
        exit;
    } else {
        $errore = "Credenziali non valide.";
    }
}
?>

<?php include('../includes/header.php'); ?>
<div class="container mt-5">
    <h2>Login</h2>
    <?php if (isset($errore)) echo "<div class='alert alert-danger'>$errore</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Nome utente</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Accedi</button>
    </form>
</div>
<?php include('../includes/footer.php'); ?>
