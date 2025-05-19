<?php
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
if ($_SESSION['user']['ruolo'] === 'viewer') {
    die('<div class="alert alert-danger">Non hai i permessi per creare squadre.</div>');
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

    $stmt = $conn->prepare("DELETE FROM squadre WHERE id = ? AND id_utente = ?");
    $stmt->bind_param("ii", $id_squadra, $id_utente);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

// Recupero dati squadra per conferma
$stmt = $conn->prepare("SELECT * FROM squadre WHERE id = ? AND id_utente = ?");
$stmt->bind_param("ii", $id_squadra, $id_utente);
$stmt->execute();
$result = $stmt->get_result();

if (!$squadra = $result->fetch_assoc()) {
    die("Squadra non trovata.");
}
?>

<div class="container mt-4">
    <h2>Conferma Eliminazione</h2>
    <p>Sei sicuro di voler eliminare la squadra <strong><?= htmlspecialchars($squadra['nome']) ?></strong>?</p>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-danger">Sì, Elimina</button>
        <a href="index.php" class="btn btn-secondary">Annulla</a>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
