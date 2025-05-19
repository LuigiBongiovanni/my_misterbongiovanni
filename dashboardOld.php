<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}
include('config/db.php');
include('includes/header.php');
include('includes/navbar.php');
?>
<div class="container mt-4">
    <h2>Benvenuto, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h2>
    <p>Questa è la tua dashboard.</p>
    <!-- Prossimi eventi -->
</div>
<?php include('includes/footer.php'); ?>
