<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$id_utente = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $ora = $_POST['ora'];
    $obiettivo = $_POST['obiettivo'];
    $id_squadra = $_POST['id_squadra'];
    $id_materiale = $_POST['id_materiale'] ?? null;

    $stmt = $conn->prepare("INSERT INTO allenamenti (data, ora, obiettivo, id_squadra, id_materiale) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $data, $ora, $obiettivo, $id_squadra, $id_materiale);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

// squadre
$squadre = $conn->prepare("SELECT id, nome FROM squadre WHERE id_utente = ?");
$squadre->bind_param("i", $id_utente);
$squadre->execute();
$res_squadre = $squadre->get_result();

// materiale
$materiale = $conn->query("SELECT id, nome FROM materiale");
?>

<div class="container mt-4">
    <h2>Nuovo Allenamento</h2>
    <form method="post">
        <div class="mb-3"><label>Data</label><input type="date" name="data" class="form-control" required></div>
        <div class="mb-3"><label>Ora</label><input type="time" name="ora" class="form-control" required></div>
        <div class="mb-3"><label>Obiettivo</label><textarea name="obiettivo" class="form-control" required></textarea></div>
        <div class="mb-3">
            <label>Squadra</label>
            <select name="id_squadra" class="form-select" required>
                <?php while ($s = $res_squadre->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nome']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Materiale Didattico</label>
            <select name="id_materiale" class="form-select">
                <option value="">-- Nessuno --</option>
                <?php while ($m = $materiale->fetch_assoc()): ?>
                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nome']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Crea</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
