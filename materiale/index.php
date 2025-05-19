<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$is_viewer = ($_SESSION['user']['ruolo'] === 'viewer');

// Recupera materiali con autore
$sql = "SELECT m.*, u.username AS autore 
        FROM materiale m 
        LEFT JOIN utenti u ON m.id_utente = u.id 
        ORDER BY m.data_upload DESC";
$result = $conn->query($sql);

// Recupera allenamenti per la select (solo se non viewer)
$allenamenti = [];
if (!$is_viewer) {
    $res_all = $conn->query("SELECT a.id, a.data, a.ora, s.nome AS squadra FROM allenamenti a LEFT JOIN squadre s ON a.id_squadra = s.id ORDER BY a.data DESC");
    while ($row = $res_all->fetch_assoc()) {
        $allenamenti[] = $row;
    }
}
?>

<div class="container mt-4">
    <h2>Materiale Didattico</h2>
    <?php if (!$is_viewer): ?>
        <a href="upload.php" class="btn btn-success mb-3">+ Carica Nuovo</a>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tipologia</th>
                <th>Descrizione</th>
                <th>File</th>
                <th>Data</th>
                <th>Autore</th>
                <?php if (!$is_viewer): ?>
                    <th>Associa ad Allenamento</th>
                    <th>Azioni</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= htmlspecialchars($row['descrizione']) ?></td>
                <td>
                    <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank">Apri</a>
                </td>
                <td>
                    <?php
                        $data_it = $row['data_upload'] ? date('d/m/Y', strtotime($row['data_upload'])) : '';
                        echo $data_it;
                    ?>
                </td>
                <td><?= htmlspecialchars($row['autore']) ?></td>
                <?php if (!$is_viewer): ?>
                <td>
                    <form method="post" class="d-flex flex-column flex-md-row gap-1">
                        <input type="hidden" name="materiale_id" value="<?= $row['id'] ?>">
                        <select name="allenamento_id" class="form-select form-select-sm" required>
                            <option value="">Scegli allenamento</option>
                            <?php foreach ($allenamenti as $all): ?>
                                <option value="<?= $all['id'] ?>">
                                    <?= date('d/m/Y', strtotime($all['data'])) ?> <?= htmlspecialchars($all['ora']) ?> - <?= htmlspecialchars($all['squadra']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="posizione" class="form-select form-select-sm" required>
                            <option value="">Posizione</option>
                            <option value="1">Materiale 1</option>
                            <option value="2">Materiale 2</option>
                            <option value="3">Materiale 3</option>
                            <option value="4">Materiale 4</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Associa</button>
                    </form>
                </td>
                <td>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo materiale?')">Elimina</a>
                </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>