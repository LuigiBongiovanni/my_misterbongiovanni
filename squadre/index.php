<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$is_viewer = ($_SESSION['user']['ruolo'] === 'viewer');

if ($is_viewer) {
    // Utente viewer: può vedere solo la squadra assegnata, nessuna modifica
    $id_squadra = $_SESSION['user']['id_squadra'];
    $sql = "SELECT * FROM squadre WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_squadra);
} else {
    // Utente normale: vede tutte le sue squadre e può modificarle
    $id_utente = $_SESSION['user']['id'];
    $sql = "SELECT * FROM squadre WHERE id_utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_utente);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <h2>Le Mie Squadre</h2>
    <?php if (!$is_viewer): ?>
        <a href="crea.php" class="btn btn-primary mb-3">+ Crea Nuova Squadra</a>
    <?php endif; ?>
    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($row['nome']) ?></strong><br>
                    <?= htmlspecialchars($row['descrizione']) ?>
                </li>
                <?php if (!$is_viewer): ?>
                <div class="mt-2">
                    <a href="modifica.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Modifica</a>
                    <a href="elimina.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger">Elimina</a>
                </div>
                <?php endif; ?>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Non hai ancora creato nessuna squadra.</p>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>
