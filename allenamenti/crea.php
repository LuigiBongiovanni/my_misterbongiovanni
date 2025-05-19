<?php
include('../config/db.php');
include('../includes/header.php');

// Recupera squadre e materiali
$squadre = $conn->query("SELECT id, nome FROM squadre");
$materiale = $conn->query("SELECT id, nome FROM materiale");
$materiali = [];
while ($m = $materiale->fetch_assoc()) {
    $materiali[] = $m;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $ora = $_POST['ora'];
    $obiettivo = $_POST['obiettivo'];
    $id_squadra = $_POST['id_squadra'];
    $id_materiale_1 = !empty($_POST['id_materiale_1']) ? $_POST['id_materiale_1'] : null;
    $id_materiale_2 = !empty($_POST['id_materiale_2']) ? $_POST['id_materiale_2'] : null;
    $id_materiale_3 = !empty($_POST['id_materiale_3']) ? $_POST['id_materiale_3'] : null;
    $id_materiale_4 = !empty($_POST['id_materiale_4']) ? $_POST['id_materiale_4'] : null;

    $stmt = $conn->prepare("INSERT INTO allenamenti (data, ora, obiettivo, id_squadra, id_materiale_1, id_materiale_2, id_materiale_3, id_materiale_4) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiiii", $data, $ora, $obiettivo, $id_squadra, $id_materiale_1, $id_materiale_2, $id_materiale_3, $id_materiale_4);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<div class="container mt-4">
    <h2>Nuovo Allenamento</h2>
    <form method="post">
        <div class="mb-3">
            <label>Data</label>
            <input type="date" name="data" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Ora</label>
            <input type="time" name="ora" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Obiettivo</label>
            <input type="text" name="obiettivo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Squadra</label>
            <select name="id_squadra" class="form-select" required>
                <?php while ($sq = $squadre->fetch_assoc()): ?>
                    <option value="<?= $sq['id'] ?>"><?= htmlspecialchars($sq['nome']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="mb-3">
            <label>Materiale Didattico <?= $i ?></label>
            <select name="id_materiale_<?= $i ?>" class="form-select">
                <option value="">-- Nessuno --</option>
                <?php foreach ($materiali as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endfor; ?>
        <button type="submit" class="btn btn-success">Crea</button>
    </form>
</div>
<?php include('../includes/footer.php'); ?>