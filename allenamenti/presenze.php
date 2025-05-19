<?php
session_start();
require_once('../config/db.php');

// --- ESPORTAZIONE PDF: deve essere PRIMA di qualsiasi output HTML ---
if (isset($_GET['id']) && isset($_GET['export']) && $_GET['export'] === 'pdf') {
    $id_allenamento = intval($_GET['id']);

    // Recupera info allenamento e squadra
    $sql = "SELECT a.*, s.nome AS squadra FROM allenamenti a LEFT JOIN squadre s ON a.id_squadra = s.id WHERE a.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_allenamento);
    $stmt->execute();
    $allenamento = $stmt->get_result()->fetch_assoc();

    // Recupera giocatori della squadra
    $id_squadra = $allenamento['id_squadra'];
    $giocatori = [];
    $res_gioc = $conn->query("SELECT id, nome FROM giocatori WHERE id_squadra = $id_squadra ORDER BY nome");
    while ($g = $res_gioc->fetch_assoc()) {
        $giocatori[] = $g;
    }

    // Recupera presenze già segnate
    $presenze = [];
    $res_pres = $conn->query("SELECT id_giocatore FROM presenze WHERE id_allenamento = $id_allenamento");
    while ($p = $res_pres->fetch_assoc()) {
        $presenze[] = $p['id_giocatore'];
    }

    require_once('../includes/fpdf.php');
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'Presenze Allenamento',0,1,'C');
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,'Data: '.date('d/m/Y', strtotime($allenamento['data'])),0,1);
    $pdf->Cell(0,8,'Ora: '.htmlspecialchars($allenamento['ora']),0,1);
    $pdf->Cell(0,8,'Squadra: '.htmlspecialchars($allenamento['squadra']),0,1);
    $pdf->Ln(5);

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(100,8,'Giocatore',1);
    $pdf->Cell(40,8,'Presente',1);
    $pdf->Ln();

    $pdf->SetFont('Arial','',12);
    foreach ($giocatori as $g) {
        $pdf->Cell(100,8,utf8_decode($g['nome']),1);
        $presente = in_array($g['id'], $presenze) ? 'SI' : 'NO';
        $pdf->Cell(40,8,$presente,1);
        $pdf->Ln();
    }

    $pdf->Output('I', 'presenze_allenamento_'.$id_allenamento.'.pdf');
    exit;
}

// --- FINE BLOCCO PDF ---

include('../includes/header.php');
include('../includes/navbar.php');

if (!isset($_GET['id'])) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ID allenamento non specificato.</div></div>";
    include('../includes/footer.php');
    exit;
}

$id_allenamento = intval($_GET['id']);

// Recupera info allenamento e squadra
$sql = "SELECT a.*, s.nome AS squadra FROM allenamenti a LEFT JOIN squadre s ON a.id_squadra = s.id WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_allenamento);
$stmt->execute();
$allenamento = $stmt->get_result()->fetch_assoc();

if (!$allenamento) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Allenamento non trovato.</div></div>";
    include('../includes/footer.php');
    exit;
}

// Recupera giocatori della squadra
$id_squadra = $allenamento['id_squadra'];
$giocatori = [];
$res_gioc = $conn->query("SELECT id, nome FROM giocatori WHERE id_squadra = $id_squadra ORDER BY nome");
while ($g = $res_gioc->fetch_assoc()) {
    $giocatori[] = $g;
}

// Recupera presenze già segnate
$presenze = [];
$res_pres = $conn->query("SELECT id_giocatore FROM presenze WHERE id_allenamento = $id_allenamento");
while ($p = $res_pres->fetch_assoc()) {
    $presenze[] = $p['id_giocatore'];
}

// BLOCCO PER VIEWER: nessuna modifica
$is_viewer = ($_SESSION['user']['ruolo'] === 'viewer');

// Gestione salvataggio presenze SOLO SE NON viewer
if (!$is_viewer && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cancella presenze precedenti
    if (!$conn->query("DELETE FROM presenze WHERE id_allenamento = $id_allenamento")) {
        echo "<div class='alert alert-danger'>Errore DELETE: " . $conn->error . "</div>";
    }
    // Inserisci nuove presenze
    if (isset($_POST['presente']) && is_array($_POST['presente'])) {
        $stmt = $conn->prepare("INSERT INTO presenze (id_allenamento, id_giocatore) VALUES (?, ?)");
        foreach ($_POST['presente'] as $id_giocatore) {
            $id_giocatore = intval($id_giocatore);
            $stmt->bind_param("ii", $id_allenamento, $id_giocatore);
            if (!$stmt->execute()) {
                echo "<div class='alert alert-danger'>Errore INSERT: " . $stmt->error . "</div>";
            }
        }
    }
    echo "<div class='container mt-4'><div class='alert alert-success'>Presenze salvate!</div></div>";
    // Aggiorna l'array delle presenze dopo il salvataggio
    $presenze = [];
    $res_pres = $conn->query("SELECT id_giocatore FROM presenze WHERE id_allenamento = $id_allenamento");
    while ($p = $res_pres->fetch_assoc()) {
        $presenze[] = $p['id_giocatore'];
    }
}
?>

<div class="container mt-4">
    <h2>Presenze Allenamento</h2>
    <p>
        <strong>Data:</strong> <?= date('d/m/Y', strtotime($allenamento['data'])) ?><br>
        <strong>Ora:</strong> <?= htmlspecialchars($allenamento['ora']) ?><br>
        <strong>Squadra:</strong> <?= htmlspecialchars($allenamento['squadra']) ?>
    </p>
    <form method="post">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Giocatore</th>
                    <th>Presente</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($giocatori as $g): ?>
                <tr>
                    <td><?= htmlspecialchars($g['nome']) ?></td>
                    <td>
                        <input type="checkbox" name="presente[]" value="<?= $g['id'] ?>" <?= in_array($g['id'], $presenze) ? 'checked' : '' ?> <?= $is_viewer ? 'disabled' : '' ?>>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!$is_viewer): ?>
            <button type="submit" class="btn btn-success">Salva Presenze</button>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Torna agli allenamenti</a>
        <a href="presenze.php?id=<?= $id_allenamento ?>&export=pdf" class="btn btn-primary" target="_blank">Esporta PDF</a>
    </form>
</div>

<?php include('../includes/footer.php'); ?>