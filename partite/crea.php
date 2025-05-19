<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$id_utente = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $ora = $_POST['ora'];
    $avversario = $_POST['avversario'];
    $luogo = $_POST['luogo'];
    $risultato = $_POST['risultato'];
    $id_squadra = $_POST['id_squadra'];

    $stmt = $conn->prepare("INSERT INTO partite (data, ora, avversario, luogo, risultato, id_squadra) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $data, $ora, $avversario, $luogo, $risultato, $id_squadra);
    $stmt->execute();

    // Mostra conferma con data in formato italiano
    echo '<div class="container mt-4">';
    echo "<div class='alert alert-success'>Partita inserita con successo!<br>";
    echo "Data partita: <strong>" . date('d/m/Y', strtotime($data)) . "</strong></div>";
    echo '<a href="index.php" class="btn btn-primary">Torna all\'elenco partite</a>';
    echo '</div>';
    include('../includes/footer.php');
    exit;
}

$squadre = $conn->prepare("SELECT id, nome FROM squadre WHERE id_utente = ?");
$squadre->bind_param("i", $id_utente);
$squadre->execute();
$res_squadre = $squadre->get_result();
?>

<div class="container mt-4">
    <h2>Nuova Partita</h2>
    <form method="post">
        <div class="mb-3"><label>Data</label><input type="date" name="data" class="form-control" required></div>
        <div class="mb-3"><label>Ora</label><input type="time" name="ora" class="form-control" required></div>
        <div class="mb-3"><label>Avversario</label><input type="text" name="avversario" class="form-control" required></div>
        <div class="mb-3"><label>Luogo</label><input type="text" name="luogo" class="form-control" required></div>
        <div class="mb-3"><label>Risultato</label><input type="text" name="risultato" class="form-control"></div>
        <div class="mb-3">
            <label>Squadra</label>
            <select name="id_squadra" class="form-select" required>
                <?php while ($s = $res_squadre->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nome']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salva</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>