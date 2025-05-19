<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
    $id_utente = $_SESSION['user']['id']; // Prendi l'id dell'utente loggato

    if ($_FILES['file']['error'] === 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $filename = basename($_FILES['file']['name']);
        $file_path = $upload_dir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("INSERT INTO materiale (nome, descrizione, file_path, id_utente) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $nome, $descrizione, $file_path, $id_utente);
            $stmt->execute();

            header("Location: index.php");
            exit;
        } else {
            $error = "Errore durante il caricamento del file.";
        }
    } else {
        $error = "Errore nel file selezionato.";
    }
}
?>

<div class="container mt-4">
    <h2>Carica Materiale</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descrizione</label>
            <textarea name="descrizione" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>File</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Carica</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>