<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$id_utente = $_SESSION['user']['id'];
$sql = "SELECT a.*, s.nome AS squadra, m.nome AS materiale 
        FROM allenamenti a
        LEFT JOIN squadre s ON a.id_squadra = s.id
        LEFT JOIN materiale m ON a.id_materiale = m.id
        WHERE s.id_utente = ?
        ORDER BY a.data DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_utente);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <h2>Allenamenti</h2>
    <a href="crea.php" class="btn btn-success mb-3">+ Crea Allenamento</a>
    <table class="table">
        <thead><tr><th>Data</th><th>Ora</th><th>Squadra</th><th>Obiettivo</th><th>Materiale</th><th>Presenze</th></tr></thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['data'] ?></td>
                <td><?= $row['ora'] ?></td>
                <td><?= htmlspecialchars($row['squadra']) ?></td>
                <td><?= htmlspecialchars($row['obiettivo']) ?></td>
                <td><?= htmlspecialchars($row['materiale']) ?></td>
                <td>
                    <a href="presenze.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Gestisci</a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i> Modifica</a>
                    <a href="presenze_pdf.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-secondary">PDF</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questo allenamento?');"><i class="bi bi-trash-fill"></i> Elimina</a>  
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
