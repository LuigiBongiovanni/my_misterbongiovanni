<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}
<?php 
require_once('../includes/navbar.php');
?>
<div class="container py-4">
  <h2 class="mb-4">Dashboard</h2>
  <div class="row g-4">

    <!-- Allenamento -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-success">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-calendar-check-fill me-2"></i>Prossimo Allenamento</h5>
          <p class="card-text">15 Maggio - 17:30</p>
          <a href="/allenamenti/index.php" class="btn btn-light btn-sm">Vai alla sezione</a>
        </div>
      </div>
    </div>

    <!-- Partita -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-danger">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-trophy-fill me-2"></i>Prossima Partita</h5>
          <p class="card-text">19 Maggio - vs Team XYZ</p>
          <a href="/partite/index.php" class="btn btn-light btn-sm">Vai alla sezione</a>
        </div>
      </div>
    </div>

    <!-- Squadre -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-primary">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-people-fill me-2"></i>Squadre Gestite</h5>
          <p class="card-text">3 squadre registrate</p>
          <a href="/squadre/index.php" class="btn btn-light btn-sm">Visualizza squadre</a>
        </div>
      </div>
    </div>

    <!-- Materiale -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-dark bg-warning">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-folder-fill me-2"></i>Materiale Didattico</h5>
          <p class="card-text">10 file caricati</p>
          <a href="/materiale/index.php" class="btn btn-dark btn-sm">Apri archivio</a>
        </div>
      </div>
    </div>

    <!-- Presenze -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-info">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-person-check-fill me-2"></i>Presenze Allenamenti</h5>
          <p class="card-text">Controlla chi partecipa</p>
          <a href="/presenze/index.php" class="btn btn-light btn-sm">Gestisci presenze</a>
        </div>
      </div>
    </div>

  </div>
</div>
