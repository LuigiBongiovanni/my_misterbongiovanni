<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$is_viewer = ($_SESSION['user']['ruolo'] === 'viewer');

if ($is_viewer) {
    // Mostra solo i giocatori della squadra assegnata all'utente viewer
    $id_squadra = $_SESSION['user']['id_squadra'];
    $sql = "SELECT g.*, s.nome AS squadra FROM giocatori g 
            LEFT JOIN squadre s ON g.id_squadra = s.id 
            WHERE g.id_squadra = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_squadra);
} else {
    // Mostra tutti i giocatori delle squadre dell'utente
    $id_utente = $_SESSION['user']['id'];
    $sql = "SELECT g.*, s.nome AS squadra FROM giocatori g 
            LEFT JOIN squadre s ON g.id_squadra = s.id 
            WHERE s.id_utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_utente);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <h2>Giocatori</h2>
    <?php if (!$is_viewer): ?>
        <a href="crea.php" class="btn btn-success mb-3">+ Aggiungi Giocatore</a>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Età</th>
                <th>Altezza</th>
                <th>Peso</th>
                <th>IMC</th>
                <th>Maturazione</th>
                <th>Squadra</th>
                <th></th>
                <?php if (!$is_viewer): ?>
                    <th></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($g = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($g['nome']) ?></td>
                    <td><?= $g['eta'] ?></td>
                    <td><?= $g['altezza_cm'] ?> cm</td>
                    <td><?= $g['peso_kg'] ?> kg</td>
                    <td><?= round($g['imc'], 2) ?></td>
                    <td><?= $g['classe_maturazione'] ?></td>
                    <td><?= htmlspecialchars($g['squadra']) ?></td>
                    <td><a href="scheda.php?id=<?= $g['id'] ?>" class="btn btn-sm btn-info">Scheda</a></td>
                    <?php if (!$is_viewer): ?>
                        <td>
                            <a href="delete.php?id=<?= $g['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo giocatore?')">Elimina</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>