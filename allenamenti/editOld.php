<?php
require_once('../config/db.php');
require_once('../includes/auth.php');
include('../includes/header.php');
include('../includes/navbar.php');
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

// Ottieni l’ID e i dati dell’allenamento
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM allenamenti WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$allenamento = $result->fetch_assoc();

if (!$allenamento) {
    echo "Allenamento non trovato.";
    exit;
}

// Se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $ora = $_POST['ora'];
    $obiettivo = $_POST['obiettivo'];

    $stmt = $conn->prepare("UPDATE allenamenti SET data = ?, ora = ?, obiettivo = ? WHERE id = ?");
    $stmt->bind_param("sssi", $data, $ora, $obiettivo, $id);
    $stmt->execute();

    header("Location: index.php?msg=modifica_successo");
    exit;
}
?>

<?php require_once('../includes/navbar.php'); ?>
<div class="container py-4">
  <h2>Modifica Allenamento</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Data</label>
      <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($allenamento['data']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Ora</label>
      <input type="time" name="ora" class="form-control" value="<?= htmlspecialchars($allenamento['ora']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Obiettivo</label>
      <textarea name="obiettivo" class="form-control" required><?= htmlspecialchars($allenamento['obiettivo']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Salva modifiche</button>
    <a href="index.php" class="btn btn-secondary">Annulla</a>
  </form>
</div>
