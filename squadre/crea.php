<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
if ($_SESSION['user']['ruolo'] === 'viewer') {
    die('<div class="alert alert-danger">Non hai i permessi per creare squadre.</div>');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
    $id_utente = $_SESSION['user']['id'];

    $stmt = $conn->prepare("INSERT INTO squadre (nome, descrizione, id_utente) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nome, $descrizione, $id_utente);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        $errore = "Errore durante la creazione della squadra.";
    }
}
?>

<div class="container mt-4">
    <h2>Crea Nuova Squadra</h2>
    <?php if (isset($errore)) echo "<div class='alert alert-danger'>$errore</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nome Squadra</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrizione</label>
            <textarea name="descrizione" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Crea</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
