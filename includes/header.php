<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MisterBongiovanni</title>
    <H1 align=center><strong>CoachBoard</strong></H1>
    <H6 align=center>ScuolaCalcio</H6>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    
    <!-- Stile personalizzato leggero -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
        }

        .navbar {
            background-color: #002244;
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: #fff !important;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Non modifichiamo i bg-color Bootstrap! */
        /* Manteniamo solo lo stile generale pulito */

        .btn-primary {
            background-color: #0056b3;
            border: none;
        }

        .table thead {
            background-color: #002244;
            color: white;
        }

        /* Hover generale per tutte le card */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>