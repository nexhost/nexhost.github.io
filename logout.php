<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

session_unset();
session_destroy();

session_start();
$_SESSION['flash_success'] = 'Sesión cerrada correctamente.';
header('Location: index.php');
exit;
