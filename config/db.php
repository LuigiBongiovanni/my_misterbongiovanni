<?php
$host = 'localhost';
$db   = 'my_misterbongiovanni';
$user = 'misterbongiovanni';
$pass = ''; // <-- Metti la tua password MySQL qui
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connessione fallita: ' . $conn->connect_error);
}
?>
