<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$result = $conn->query("SELECT * FROM materiale ORDER BY data_upload DESC");
?>

<div class="container mt-4">
    <h2>Materiale Didattico</h2>
    <a href="upload.php" class="btn btn-success mb-3">+ Carica Nuovo</a>

    <table class="table table-bordered">
        <thead><tr><th>Nome</th><th>Descrizione</th><th>File</th><th>Data</th><th>Azioni</th></tr></thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= htmlspecialchars($row['descrizione']) ?></td>
                <td><a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank">Apri</a></td>
                <td><?= $row['data_upload'] ?></td>
                <td>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo materiale?')">Elimina</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
