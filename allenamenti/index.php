<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$is_viewer = ($_SESSION['user']['ruolo'] === 'viewer');

if ($is_viewer) {
    $id_squadra = $_SESSION['user']['id_squadra'];
    $sql = "SELECT a.*, s.nome AS squadra, 
        m1.nome AS materiale1, m2.nome AS materiale2, m3.nome AS materiale3, m4.nome AS materiale4
        FROM allenamenti a
        LEFT JOIN squadre s ON a.id_squadra = s.id
        LEFT JOIN materiale m1 ON a.id_materiale_1 = m1.id
        LEFT JOIN materiale m2 ON a.id_materiale_2 = m2.id
        LEFT JOIN materiale m3 ON a.id_materiale_3 = m3.id
        LEFT JOIN materiale m4 ON a.id_materiale_4 = m4.id
        WHERE a.id_squadra = ?
        ORDER BY a.data DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_squadra);
} else {
    $id_utente = $_SESSION['user']['id'];
    $sql = "SELECT a.*, s.nome AS squadra, 
        m1.nome AS materiale1, m2.nome AS materiale2, m3.nome AS materiale3, m4.nome AS materiale4
        FROM allenamenti a
        LEFT JOIN squadre s ON a.id_squadra = s.id
        LEFT JOIN materiale m1 ON a.id_materiale_1 = m1.id
        LEFT JOIN materiale m2 ON a.id_materiale_2 = m2.id
        LEFT JOIN materiale m3 ON a.id_materiale_3 = m3.id
        LEFT JOIN materiale m4 ON a.id_materiale_4 = m4.id
        WHERE s.id_utente = ?
        ORDER BY a.data DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_utente);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <h2>Allenamenti</h2>
    <?php if (!$is_viewer): ?>
        <a href="crea.php" class="btn btn-success mb-3">+ Crea Allenamento</a>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Data</th>
                <th>Ora</th>
                <th>Squadra</th>
                <th>Obiettivo</th>
                <th>Materiali</th>
                <th>Presenze</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($row['data'])) ?></td>
                <td><?= htmlspecialchars($row['ora']) ?></td>
                <td><?= htmlspecialchars($row['squadra']) ?></td>
                <td><?= htmlspecialchars($row['obiettivo']) ?></td>
                <td>
                    <?php
                        $materiali = [];
                        if ($row['materiale1']) $materiali[] = htmlspecialchars($row['materiale1']);
                        if ($row['materiale2']) $materiali[] = htmlspecialchars($row['materiale2']);
                        if ($row['materiale3']) $materiali[] = htmlspecialchars($row['materiale3']);
                        if ($row['materiale4']) $materiali[] = htmlspecialchars($row['materiale4']);
                        echo implode(', ', $materiali);
                    ?>
                </td>
                <td>
                    <a href="presenze.php?id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm">Gestisci</a>
                </td>
                <td>
                    <a href="visualizza.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">Visualizza</a>
                    <?php if (!$is_viewer): ?>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Modifica</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo allenamento?')">Elimina</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>