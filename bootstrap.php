<?php

declare(strict_types=1);

session_start();

define('APP_NAME', 'EventFlow Pro');
define('BASE_URL', '/');

require_once __DIR__ . '/config/database.php';

function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function isAuthenticated(): bool
{
    return isset($_SESSION['user_id']);
}

function requireAuth(): void
{
    if (!isAuthenticated()) {
        $_SESSION['flash_error'] = 'Debes iniciar sesión para continuar.';
        header('Location: login.php');
        exit;
    }
}

function csrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string) $token);
}

function flash(string $key): ?string
{
    if (!isset($_SESSION[$key])) {
        return null;
    }

    $message = $_SESSION[$key];
    unset($_SESSION[$key]);

    return $message;
}
