<?php
session_start();
require_once('../config/db.php');

if ($_SESSION['user']['ruolo'] === 'viewer') {
    die('<div class="alert alert-danger">Non hai i permessi per eliminare partite.</div>');
}
require_once('../config/db.php');
$id = (int)$_GET['id'];
$stmt = $conn->prepare("DELETE FROM partite WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;
