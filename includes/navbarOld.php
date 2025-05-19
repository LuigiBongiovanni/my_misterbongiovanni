<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard.php">MisterBongiovanni</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/squadre/index.php">Squadre</a></li>
                <li class="nav-item"><a class="nav-link" href="/giocatori/index.php">Giocatori</a></li>
                <li class="nav-item"><a class="nav-link" href="/allenamenti/index.php">Allenamenti</a></li>
                <li class="nav-item"><a class="nav-link" href="/partite/index.php">Partite</a></li>
                <li class="nav-item"><a class="nav-link" href="/materiale/index.php">Materiale</a></li>
                <li class="nav-item"><a class="nav-link" href="/dashboard/index.php">Dashboard</a></li>
            </ul>
            
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">Ciao, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/auth/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
