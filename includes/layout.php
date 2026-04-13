<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

function renderHeader(string $title): void
{
    $loggedIn = isAuthenticated();
    $userName = $_SESSION['user_name'] ?? '';
    echo '<!DOCTYPE html>';
    echo '<html lang="es">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . esc($title) . ' | ' . APP_NAME . '</title>';
    echo '<script src="https://cdn.tailwindcss.com"></script>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link rel="stylesheet" href="assets/css/custom.css">';
    echo '</head><body class="bg-slate-50 text-slate-800">';

    echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">';
    echo '<div class="container">';
    echo '<a class="navbar-brand fw-bold" href="index.php">' . APP_NAME . '</a>';
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">';
    echo '<span class="navbar-toggler-icon"></span></button>';
    echo '<div class="collapse navbar-collapse" id="menu"><ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">';
    echo '<li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>';
    if ($loggedIn) {
        echo '<li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="events.php">Mis eventos</a></li>';
        echo '<li class="nav-item"><span class="badge rounded-pill bg-info text-dark">' . esc($userName) . '</span></li>';
        echo '<li class="nav-item"><a class="btn btn-outline-light btn-sm" href="logout.php">Salir</a></li>';
    } else {
        echo '<li class="nav-item"><a class="btn btn-outline-light btn-sm me-2" href="login.php">Iniciar sesión</a></li>';
        echo '<li class="nav-item"><a class="btn btn-primary btn-sm" href="register.php">Crear cuenta</a></li>';
    }
    echo '</ul></div></div></nav>';

    if ($success = flash('flash_success')) {
        echo '<div class="container mt-3"><div class="alert alert-success">' . esc($success) . '</div></div>';
    }

    if ($error = flash('flash_error')) {
        echo '<div class="container mt-3"><div class="alert alert-danger">' . esc($error) . '</div></div>';
    }
}

function renderFooter(): void
{
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
    echo '<script src="assets/js/app.js"></script>';
    echo '</body></html>';
}
