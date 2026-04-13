<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$id = (int) ($_GET['id'] ?? 0);

$query = $pdo->prepare('SELECT e.*, u.name AS organizer FROM events e JOIN users u ON u.id = e.user_id WHERE e.id = :id LIMIT 1');
$query->execute(['id' => $id]);
$event = $query->fetch();

if (!$event) {
    $_SESSION['flash_error'] = 'Evento no encontrado.';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $_SESSION['flash_error'] = 'Token CSRF inválido.';
        header('Location: view_event.php?id=' . $id);
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name === '' || $email === '') {
        $_SESSION['flash_error'] = 'Debes completar todos los datos.';
    } else {
        $insert = $pdo->prepare('INSERT INTO registrations(event_id, attendee_name, attendee_email) VALUES(:event_id, :attendee_name, :attendee_email)');
        $insert->execute([
            'event_id' => $id,
            'attendee_name' => $name,
            'attendee_email' => $email,
        ]);
        $_SESSION['flash_success'] = 'Registro completado con éxito.';
        header('Location: view_event.php?id=' . $id);
        exit;
    }
}

$registrations = $pdo->prepare('SELECT attendee_name, attendee_email, created_at FROM registrations WHERE event_id = :id ORDER BY created_at DESC');
$registrations->execute(['id' => $id]);
$list = $registrations->fetchAll();

renderHeader('Detalle de evento');
?>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h1 class="h3"><?= esc($event['title']) ?></h1>
                    <p class="text-muted mb-2">Organizado por <?= esc($event['organizer']) ?></p>
                    <p><strong>Fecha inicio:</strong> <?= esc($event['start_date']) ?></p>
                    <p><strong>Fecha fin:</strong> <?= esc($event['end_date']) ?></p>
                    <p><strong>Ubicación:</strong> <?= esc($event['location']) ?></p>
                    <p class="mb-0"><?= nl2br(esc($event['description'])) ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h5">Registrarme</h2>
                    <form method="post" class="d-grid gap-3">
                        <input type="hidden" name="csrf_token" value="<?= esc(csrfToken()) ?>">
                        <input class="form-control" name="name" placeholder="Tu nombre" required>
                        <input class="form-control" type="email" name="email" placeholder="Tu correo" required>
                        <button class="btn btn-primary">Confirmar asistencia</button>
                    </form>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h6">Asistentes registrados (<?= count($list) ?>)</h3>
                    <ul class="list-group list-group-flush small">
                        <?php foreach ($list as $item): ?>
                            <li class="list-group-item px-0">
                                <strong><?= esc($item['attendee_name']) ?></strong><br>
                                <span class="text-muted"><?= esc($item['attendee_email']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php renderFooter(); ?>
