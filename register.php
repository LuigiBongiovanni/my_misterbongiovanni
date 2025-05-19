<?php
session_start();
require_once('config/db.php');
include('includes/header.php');

// Recupera le squadre per la select
$squadre = [];
$res = $conn->query("SELECT id, nome FROM squadre ORDER BY nome");
while ($row = $res->fetch_assoc()) {
    $squadre[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $id_squadra = intval($_POST['id_squadra']);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO utenti (username, password, ruolo, id_squadra) VALUES (?, ?, 'viewer', ?)");
    $stmt->bind_param("ssi", $username, $password_hash, $id_squadra);
    $stmt->execute();

    // Login automatico dopo la registrazione
    $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    $_SESSION['user'] = $user;
    header('Location: dashboard/index.php');
    exit;
}
?>

<div class="container mt-5">
    <h2>Registrazione</h2>

    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="id_squadra" class="form-label">Squadra da visualizzare</label>
            <select name="id_squadra" id="id_squadra" class="form-control" required>
                <option value="">Seleziona una squadra</option>
                <?php foreach ($squadre as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Registrati</button>
    </form>
    
    <p class="mt-3">Hai già un account? <a href="index.php">Accedi</a></p>
</div>

<?php include('includes/footer.php'); ?>