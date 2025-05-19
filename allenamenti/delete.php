<?php
require_once('../config/db.php');
require_once('../includes/auth.php');

// Verifica login
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

// Verifica che ci sia un ID valido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    $id_utente = $_SESSION['user']['id'];

    // Verifica che l'allenamento appartenga a una squadra gestita dall'utente
    $stmt = $conn->prepare("
        DELETE a FROM allenamenti a
        JOIN squadre s ON a.id_squadra = s.id
        WHERE a.id = ? AND s.id_utente = ?
    ");
    $stmt->bind_param("ii", $id, $id_utente);
    $stmt->execute();

    // Controlla se è stato effettivamente eliminato
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?msg=allenamento_eliminato");
    } else {
        header("Location: index.php?msg=errore_autorizzazione");
    }
    exit;

} else {
    header("Location: index.php?msg=errore_parametro");
    exit;
}
?>
