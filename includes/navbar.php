<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/my_misterbongiovanni/dashboard/index.php">CoachBoard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/my_misterbongiovanni/squadre/index.php">Squadre</a></li>
        <li class="nav-item"><a class="nav-link" href="/my_misterbongiovanni/giocatori/index.php">Giocatori</a></li>
        <li class="nav-item"><a class="nav-link" href="/my_misterbongiovanni/allenamenti/index.php">Allenamenti</a></li>
        <li class="nav-item"><a class="nav-link" href="/my_misterbongiovanni/partite/index.php">Partite</a></li>
        <?php if (!isset($_SESSION['user']) || $_SESSION['user']['ruolo'] !== 'viewer'): ?>
          <li class="nav-item"><a class="nav-link" href="/my_misterbongiovanni/materiale/index.php">Materiale</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item">
            <span class="nav-link">👋 <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="/my_misterbongiovanni/logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/my_misterbongiovanni/index.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>