<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $_SESSION['flash_error'] = 'Token CSRF inválido.';
        header('Location: register.php');
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $_SESSION['flash_error'] = 'Todos los campos son obligatorios.';
    } else {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $check->execute(['email' => $email]);

        if ($check->fetch()) {
            $_SESSION['flash_error'] = 'El correo ya está registrado.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $insert = $pdo->prepare('INSERT INTO users(name, email, password_hash) VALUES(:name, :email, :password_hash)');
            $insert->execute([
                'name' => $name,
                'email' => $email,
                'password_hash' => $hash,
            ]);
            $_SESSION['flash_success'] = 'Cuenta creada correctamente. Ya puedes iniciar sesión.';
            header('Location: login.php');
            exit;
        }
    }
}

renderHeader('Crear cuenta');
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4">
                    <h1 class="h3 mb-3">Crear cuenta</h1>
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= esc(csrfToken()) ?>">
                        <div class="mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control" name="name" required></div>
                        <div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="email" required></div>
                        <div class="mb-3"><label class="form-label">Contraseña</label><input type="password" class="form-control" name="password" required></div>
                        <button class="btn btn-dark w-100">Crear cuenta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php renderFooter(); ?>
