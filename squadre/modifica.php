<?php
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
if ($_SESSION['user']['ruolo'] === 'viewer') {
    die('<div class="alert alert-danger">Non hai i permessi per modificare squadre.</div>');
}
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_utente = $_SESSION['user']['id'];

if (!isset($_GET['id'])) {
    die("ID squadra non specificato.");
}

$id_squadra = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF non valido.");
    }

    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];

    $stmt = $conn->prepare("UPDATE squadre SET nome = ?, descrizione = ? WHERE id = ? AND id_utente = ?");
    $stmt->bind_param("ssii", $nome, $descrizione, $id_squadra, $id_utente);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM squadre WHERE id = ? AND id_utente = ?");
$stmt->bind_param("ii", $id_squadra, $id_utente);
$stmt->execute();
$result = $stmt->get_result();

if (!$squadra = $result->fetch_assoc()) {
    die("Squadra non trovata o accesso negato.");
}
?>

<div class="container mt-4">
    <h2>Modifica Squadra</h2>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($squadra['nome']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrizione</label>
            <textarea name="descrizione" class="form-control"><?= htmlspecialchars($squadra['descrizione']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        <a href="index.php" class="btn btn-secondary">Annulla</a>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
