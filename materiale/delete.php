<?php
session_start();
require_once('../config/db.php');

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT file_path FROM materiale WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$mat = $result->fetch_assoc();

if ($mat && file_exists($mat['file_path'])) {
    unlink($mat['file_path']);
}

$conn->query("DELETE FROM materiale WHERE id = $id");

header("Location: index.php");
exit;
