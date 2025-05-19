<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

// BLOCCO PER VIEWER
if ($_SESSION['user']['ruolo'] === 'viewer') {
    die('<div class="alert alert-danger">Non hai i permessi per aggiungere giocatori.</div>');
}

$id_utente = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $eta = (int)$_POST['eta'];
    $altezza_cm = (float)$_POST['altezza'];
    $peso_kg = (float)$_POST['peso'];
    $id_squadra = (int)$_POST['id_squadra'];
    $altezza_m = $altezza_cm / 100;
    $imc = $peso_kg / ($altezza_m * $altezza_m);

    if ($imc < 15) $classe = 'A1';
    elseif ($imc < 17) $classe = 'A2';
    elseif ($imc < 20) $classe = 'A3';
    elseif ($imc < 25) $classe = 'A4';
    else $classe = 'A5';

    $stmt = $conn->prepare("INSERT INTO giocatori (nome, eta, altezza_cm, peso_kg, imc, classe_maturazione, id_squadra) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiddsi", $nome, $eta, $altezza_cm, $peso_kg, $imc, $classe, $id_squadra);

    if ($stmt->execute()) {
        header("Location: index.php?msg=giocatore_aggiunto");
        exit;
    } else {
        $errore = "Errore durante l'inserimento del giocatore: " . $stmt->error;
    }
}

$squadre = $conn->prepare("SELECT id, nome FROM squadre WHERE id_utente = ?");
$squadre->bind_param("i", $id_utente);
$squadre->execute();
$res_squadre = $squadre->get_result();
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Aggiungi Nuovo Giocatore</h4>
        </div>
        <div class="card-body">
            <?php if (isset($errore)) echo "<div class='alert alert-danger'>$errore</div>"; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Età</label>
                    <input type="number" name="eta" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Altezza (cm)</label>
                    <input type="number" name="altezza" class="form-control" step="0.1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" name="peso" class="form-control" step="0.1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Squadra</label>
                    <select name="id_squadra" class="form-select" required>
                        <?php while ($s = $res_squadre->fetch_assoc()): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nome']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Salva Giocatore</button>
            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>