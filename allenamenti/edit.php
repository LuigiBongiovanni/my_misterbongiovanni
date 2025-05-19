<?php
include('../config/db.php');
include('../includes/header.php');

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM allenamenti WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$allenamento = $stmt->get_result()->fetch_assoc();

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

    $stmt = $conn->prepare("UPDATE allenamenti SET data=?, ora=?, obiettivo=?, id_squadra=?, id_materiale_1=?, id_materiale_2=?, id_materiale_3=?, id_materiale_4=? WHERE id=?");
    $stmt->bind_param("sssiiiiii", $data, $ora, $obiettivo, $id_squadra, $id_materiale_1, $id_materiale_2, $id_materiale_3, $id_materiale_4, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<div class="container mt-4">
    <h2>Modifica Allenamento</h2>
    <form method="post">
        <div class="mb-3">
            <label>Data</label>
            <input type="date" name="data" class="form-control" value="<?= $allenamento['data'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Ora</label>
            <input type="time" name="ora" class="form-control" value="<?= $allenamento['ora'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Obiettivo</label>
            <input type="text" name="obiettivo" class="form-control" value="<?= htmlspecialchars($allenamento['obiettivo']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Squadra</label>
            <select name="id_squadra" class="form-select" required>
                <?php foreach ($squadre as $sq): ?>
                    <option value="<?= $sq['id'] ?>" <?= $allenamento['id_squadra'] == $sq['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sq['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="mb-3">
            <label>Materiale Didattico <?= $i ?></label>
            <select name="id_materiale_<?= $i ?>" class="form-select">
                <option value="">-- Nessuno --</option>
                <?php foreach ($materiali as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $allenamento['id_materiale_'.$i] == $m['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endfor; ?>
        <button type="submit" class="btn btn-primary">Salva</button>
    </form>
</div>
<?php include('../includes/footer.php'); ?>