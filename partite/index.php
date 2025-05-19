<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$is_viewer = ($_SESSION['user']['ruolo'] === 'viewer');

if ($is_viewer) {
    $id_squadra = $_SESSION['user']['id_squadra'];
    $sql = "SELECT p.*, s.nome AS squadra FROM partite p
            JOIN squadre s ON p.id_squadra = s.id
            WHERE s.id = ?
            ORDER BY p.data DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_squadra);
} else {
    $id_utente = $_SESSION['user']['id'];
    $sql = "SELECT p.*, s.nome AS squadra FROM partite p
            JOIN squadre s ON p.id_squadra = s.id
            WHERE s.id_utente = ?
            ORDER BY p.data DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_utente);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <h2>Partite</h2>
    <?php if (!$is_viewer): ?>
        <a href="crea.php" class="btn btn-success mb-3">+ Aggiungi Partita</a>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead><tr><th>Data</th><th>Ora</th><th>Squadra</th><th>Avversario</th><th>Luogo</th><th>Risultato</th><th>Azioni</th></tr></thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($row['data'])) ?></td>
                <td><?= $row['ora'] ?></td>
                <td><?= htmlspecialchars($row['squadra']) ?></td>
                <td><?= htmlspecialchars($row['avversario']) ?></td>
                <td><?= htmlspecialchars($row['luogo']) ?></td>
                <td><?= htmlspecialchars($row['risultato']) ?></td>
                <td>
                    <?php if (!$is_viewer): ?>
                        <a href="modifica.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Modifica</a>
                        <a href="elimina.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare la partita?')">Elimina</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>