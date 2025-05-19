<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$is_viewer = ($_SESSION['user']['ruolo'] === 'viewer');

if ($is_viewer) {
    $id_squadra = $_SESSION['user']['id_squadra'];

    // Prossime partite della squadra assegnata
    $sql_partite = "SELECT * FROM partite WHERE id_squadra = ? AND data >= CURDATE() ORDER BY data ASC LIMIT 5";
    $stmt_partite = $conn->prepare($sql_partite);
    $stmt_partite->bind_param("i", $id_squadra);
    $stmt_partite->execute();
    $result_partite = $stmt_partite->get_result();

    // Prossimi allenamenti della squadra assegnata
    $sql_allenamenti = "SELECT * FROM allenamenti WHERE id_squadra = ? AND data >= CURDATE() ORDER BY data ASC LIMIT 5";
    $stmt_allenamenti = $conn->prepare($sql_allenamenti);
    $stmt_allenamenti->bind_param("i", $id_squadra);
    $stmt_allenamenti->execute();
    $result_allenamenti = $stmt_allenamenti->get_result();

    // Info della squadra assegnata
    $sql_squadra = "SELECT * FROM squadre WHERE id = ?";
    $stmt_squadra = $conn->prepare($sql_squadra);
    $stmt_squadra->bind_param("i", $id_squadra);
    $stmt_squadra->execute();
    $result_squadra = $stmt_squadra->get_result();
} else {
    $id_utente = $_SESSION['user']['id'];

    // Prossime partite
    $sql_partite = "SELECT * FROM partite p
                    JOIN squadre s ON p.id_squadra = s.id
                    WHERE s.id_utente = ? AND p.data >= CURDATE()
                    ORDER BY p.data ASC LIMIT 5";
    $stmt_partite = $conn->prepare($sql_partite);
    $stmt_partite->bind_param("i", $id_utente);
    $stmt_partite->execute();
    $result_partite = $stmt_partite->get_result();

    // Prossimi allenamenti
    $sql_allenamenti = "SELECT * FROM allenamenti a
                        JOIN squadre s ON a.id_squadra = s.id
                        WHERE s.id_utente = ? AND a.data >= CURDATE()
                        ORDER BY a.data ASC LIMIT 5";
    $stmt_allenamenti = $conn->prepare($sql_allenamenti);
    $stmt_allenamenti->bind_param("i", $id_utente);
    $stmt_allenamenti->execute();
    $result_allenamenti = $stmt_allenamenti->get_result();

    // Le squadre che gestisci
    $sql_squadre = "SELECT * FROM squadre WHERE id_utente = ?";
    $stmt_squadre = $conn->prepare($sql_squadre);
    $stmt_squadre->bind_param("i", $id_utente);
    $stmt_squadre->execute();
    $result_squadre = $stmt_squadre->get_result();
}
?>

<div class="container py-5">
    <h2 class="text-center mb-4 text-primary fw-bold">📊 La tua Dashboard</h2>

    <div class="row g-4">
        <!-- Prossime Partite -->
        <div class="col-md-4">
            <div class="card border-0 shadow bg-gradient bg-primary bg-opacity-10 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">⚽ Prossime Partite</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php if ($result_partite->num_rows > 0): ?>
                            <?php while ($row = $result_partite->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <strong><?= date('d/m/Y', strtotime($row['data'])) ?> - <?= $row['ora'] ?></strong><br>
                                    <span class="text-muted">Avversario:</span> <?= htmlspecialchars($row['avversario']) ?><br>
                                    <span class="text-muted">Luogo:</span> <?= htmlspecialchars($row['luogo']) ?>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">Non ci sono partite future.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Prossimi Allenamenti -->
        <div class="col-md-4">
            <div class="card border-0 shadow bg-gradient bg-success bg-opacity-10 h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🏋️ Prossimi Allenamenti</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php if ($result_allenamenti->num_rows > 0): ?>
                            <?php while ($row = $result_allenamenti->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <strong><?= date('d/m/Y', strtotime($row['data'])) ?> - <?= $row['ora'] ?></strong><br>
                                    <span class="text-muted">Obiettivo:</span> <?= htmlspecialchars($row['obiettivo']) ?>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">Non ci sono allenamenti futuri.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Le Tue Squadre -->
        <div class="col-md-4">
            <div class="card border-0 shadow bg-gradient bg-info bg-opacity-10 h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">👥 La Tua Squadra</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php if ($is_viewer): ?>
                            <?php if ($result_squadra->num_rows > 0): ?>
                                <?php $row = $result_squadra->fetch_assoc(); ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($row['nome']) ?></strong><br>
                                    <small class="text-muted">Categoria: <?= htmlspecialchars($row['categoria']) ?></small>
                                </li>
                            <?php else: ?>
                                <li class="list-group-item text-muted">Nessuna squadra assegnata.</li>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ($result_squadre->num_rows > 0): ?>
                                <?php while ($row = $result_squadre->fetch_assoc()): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($row['nome']) ?></strong><br>
                                        <small class="text-muted">Categoria: <?= htmlspecialchars($row['categoria']) ?></small>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="list-group-item text-muted">Non gestisci ancora squadre.</li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>