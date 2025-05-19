<?php
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $stmt = $conn->prepare("INSERT INTO utenti (username, password, ruolo) VALUES (?, ?, 'viewer')");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $errore = "Errore nella registrazione.";
    }
}
?>

<?php include('../includes/header.php'); ?>
<div class="container mt-5">
    <h2>Registrazione</h2>
    <?php if (isset($errore)) echo "<div class='alert alert-danger'>$errore</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nome utente</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-success">Registrati</button>
    </form>
</div>
<?php include('../includes/footer.php'); ?>
