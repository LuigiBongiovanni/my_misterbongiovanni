<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$id = (int)$_GET['id'];

if ($_SESSION['user']['ruolo'] === 'viewer') {
    die('<div class="alert alert-danger">Non hai i permessi per modificare partite.</div>');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $ora = $_POST['ora'];
    $avversario = $_POST['avversario'];
    $luogo = $_POST['luogo'];
    $risultato = $_POST['risultato'];

    $stmt = $conn->prepare("UPDATE partite SET data=?, ora=?, avversario=?, luogo=?, risultato=? WHERE id=?");
    $stmt->bind_param("sssssi", $data, $ora, $avversario, $luogo, $risultato, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM partite WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$partita = $stmt->get_result()->fetch_assoc();
?>

<div class="container mt-4">
    <h2>Modifica Partita</h2>
    <form method="post">
        <div class="mb-3"><label>Data</label><input type="date" name="data" class="form-control" value="<?= $partita['data'] ?>" required></div>
        <div class="mb-3"><label>Ora</label><input type="time" name="ora" class="form-control" value="<?= $partita['ora'] ?>" required></div>
        <div class="mb-3"><label>Avversario</label><input type="text" name="avversario" class="form-control" value="<?= htmlspecialchars($partita['avversario']) ?>" required></div>
        <div class="mb-3"><label>Luogo</label><input type="text" name="luogo" class="form-control" value="<?= htmlspecialchars($partita['luogo']) ?>" required></div>
        <div class="mb-3"><label>Risultato</label><input type="text" name="risultato" class="form-control" value="<?= htmlspecialchars($partita['risultato']) ?>"></div>
        <button type="submit" class="btn btn-warning">Aggiorna</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
