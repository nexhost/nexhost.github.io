<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $_SESSION['flash_error'] = 'Token CSRF inválido.';
        header('Location: login.php');
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $query = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = :email LIMIT 1');
    $query->execute(['email' => $email]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['flash_success'] = 'Bienvenido de nuevo, ' . $user['name'] . '.';
        header('Location: dashboard.php');
        exit;
    }

    $_SESSION['flash_error'] = 'Credenciales inválidas.';
}

renderHeader('Iniciar sesión');
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4">
                    <h1 class="h3 mb-3">Iniciar sesión</h1>
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= esc(csrfToken()) ?>">
                        <div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="email" required></div>
                        <div class="mb-3"><label class="form-label">Contraseña</label><input type="password" class="form-control" name="password" required></div>
                        <button class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php renderFooter(); ?>
