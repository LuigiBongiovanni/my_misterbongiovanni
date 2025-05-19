<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$id_giocatore = (int)$_GET['id'];
$sql = "SELECT g.*, s.nome AS squadra FROM giocatori g
        LEFT JOIN squadre s ON g.id_squadra = s.id
        WHERE g.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_giocatore);
$stmt->execute();
$result = $stmt->get_result();

if (!$giocatore = $result->fetch_assoc()) {
    die("Giocatore non trovato.");
}
?>

<div class="container mt-4">
    <h2>Scheda Giocatore</h2>
    <p><strong>Nome:</strong> <?= htmlspecialchars($giocatore['nome']) ?></p>
    <p><strong>Età:</strong> <?= $giocatore['eta'] ?></p>
    <p><strong>Altezza:</strong> <?= $giocatore['altezza_cm'] ?> cm</p>
    <p><strong>Peso:</strong> <?= $giocatore['peso_kg'] ?> kg</p>
    <p><strong>IMC:</strong> <?= round($giocatore['imc'], 2) ?></p>
    <p><strong>Classe Maturazione:</strong> <?= $giocatore['classe_maturazione'] ?></p>
    <p><strong>Squadra:</strong> <?= htmlspecialchars($giocatore['squadra']) ?></p>
    <p><strong>Presenze:</strong> <?= $giocatore['presenze'] ?> | <strong>Assenze:</strong> <?= $giocatore['assenze'] ?></p>

<?php include('../includes/footer.php'); ?>
