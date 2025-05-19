<?php
session_start();
require_once('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

if (!isset($_GET['id'])) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ID allenamento non specificato.</div></div>";
    include('../includes/footer.php');
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT a.*, s.nome AS squadra, 
        m1.nome AS materiale1, m1.file_path AS file1,
        m2.nome AS materiale2, m2.file_path AS file2,
        m3.nome AS materiale3, m3.file_path AS file3,
        m4.nome AS materiale4, m4.file_path AS file4
        FROM allenamenti a
        LEFT JOIN squadre s ON a.id_squadra = s.id
        LEFT JOIN materiale m1 ON a.id_materiale_1 = m1.id
        LEFT JOIN materiale m2 ON a.id_materiale_2 = m2.id
        LEFT JOIN materiale m3 ON a.id_materiale_3 = m3.id
        LEFT JOIN materiale m4 ON a.id_materiale_4 = m4.id
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$allenamento = $result->fetch_assoc();

if (!$allenamento) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Allenamento non trovato.</div></div>";
    include('../includes/footer.php');
    exit;
}
?>

<div class="container mt-4">
    <h2>Dettaglio Allenamento</h2>
    <table class="table table-bordered">
        <tr>
            <th>Data</th>
            <td>
                <?php
                    $data_it = date('d/m/Y', strtotime($allenamento['data']));
                    echo $data_it;
                ?>
            </td>
        </tr>
        <tr>
            <th>Ora</th>
            <td><?= htmlspecialchars($allenamento['ora']) ?></td>
        </tr>
        <tr>
            <th>Squadra</th>
            <td><?= htmlspecialchars($allenamento['squadra']) ?></td>
        </tr>
        <tr>
            <th>Obiettivo</th>
            <td><?= htmlspecialchars($allenamento['obiettivo']) ?></td>
        </tr>
        <tr>
            <th>Materiali Allegati</th>
            <td>
                <ul>
                    <?php
                    for ($i = 1; $i <= 4; $i++) {
                        $nome = $allenamento["materiale$i"];
                        $file = $allenamento["file$i"];
                        if ($nome) {
                            echo "<li>";
                            if ($file) {
                                echo "<a href='" . htmlspecialchars($file) . "' target='_blank'>" . htmlspecialchars($nome) . "</a>";
                            } else {
                                echo htmlspecialchars($nome);
                            }
                            echo "</li>";
                        }
                    }
                    if (
                        empty($allenamento['materiale1']) &&
                        empty($allenamento['materiale2']) &&
                        empty($allenamento['materiale3']) &&
                        empty($allenamento['materiale4'])
                    ) {
                        echo "<li>Nessun materiale allegato</li>";
                    }
                    ?>
                </ul>
            </td>
        </tr>
    </table>
    <a href="index.php" class="btn btn-secondary">Torna alla lista</a>
    <a href="edit.php?id=<?= $allenamento['id'] ?>" class="btn btn-warning">Modifica</a>
    <a href="delete.php?id=<?= $allenamento['id'] ?>" class="btn btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questo allenamento?');">Elimina</a>
</div>

<?php include('../includes/footer.php'); ?>