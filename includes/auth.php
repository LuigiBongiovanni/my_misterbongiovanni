<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Controlla se l'utente è loggato.
 *
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user']) && isset($_SESSION['user']['id']);
}

/**
 * Controlla se l'utente ha il ruolo di amministratore.
 *
 * @return bool
 */
function isAdmin() {
    return isLoggedIn() && $_SESSION['user']['ruolo'] === 'admin';
}

/**
 * Reindirizza alla login se non loggato.
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit;
    }
}
