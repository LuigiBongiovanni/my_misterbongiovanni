<?php
session_start();
require_once('../config/db.php');

// Verifica che l'utente sia loggato
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

// Verifica che sia stato passato un ID valido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?msg=parametro_non_valido");
    exit;
}

$id_giocatore = (int)$_GET['id'];
$id_utente = $_SESSION['user']['id'];

// Controlla che il giocatore appartenga a una squadra dell'utente
$sql = "SELECT g.id 
        FROM giocatori g 
        JOIN squadre s ON g.id_squadra = s.id 
        WHERE g.id = ? AND s.id_utente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_giocatore, $id_utente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Il giocatore non è autorizzato o non esiste
    header("Location: index.php?msg=non_autorizzato");
    exit;
}

// Procedi con l'eliminazione
$delete = $conn->prepare("DELETE FROM giocatori WHERE id = ?");
$delete->bind_param("i",$id_giocatore);
$delete->execute();

header("Location: index.php?msg=giocatore_eliminato");
exit;
?>
