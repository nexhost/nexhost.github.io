<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';
requireAuth();

$userId = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $_SESSION['flash_error'] = 'Token CSRF inválido.';
        header('Location: events.php');
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $stmt = $pdo->prepare('INSERT INTO events(user_id, title, description, location, start_date, end_date, capacity) VALUES(:user_id, :title, :description, :location, :start_date, :end_date, :capacity)');
        $stmt->execute([
            'user_id' => $userId,
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null,
            'capacity' => (int) ($_POST['capacity'] ?? 0),
        ]);
        $_SESSION['flash_success'] = 'Evento creado correctamente.';
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM events WHERE id = :id AND user_id = :user_id');
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $_SESSION['flash_success'] = 'Evento eliminado.';
    }

    header('Location: events.php');
    exit;
}

$query = $pdo->prepare('SELECT e.*, COUNT(r.id) AS attendees FROM events e LEFT JOIN registrations r ON r.event_id = e.id WHERE e.user_id = :user_id GROUP BY e.id ORDER BY e.start_date ASC');
$query->execute(['user_id' => $userId]);
$events = $query->fetchAll();

renderHeader('Gestión de eventos');
?>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Nuevo evento</h2>
                    <form method="post" class="d-grid gap-3">
                        <input type="hidden" name="csrf_token" value="<?= esc(csrfToken()) ?>">
                        <input type="hidden" name="action" value="create">
                        <input class="form-control" name="title" placeholder="Título" required>
                        <textarea class="form-control" name="description" placeholder="Descripción" rows="4" required></textarea>
                        <input class="form-control" name="location" placeholder="Ubicación" required>
                        <input class="form-control" type="datetime-local" name="start_date" required>
                        <input class="form-control" type="datetime-local" name="end_date" required>
                        <input class="form-control" type="number" name="capacity" min="1" placeholder="Capacidad" required>
                        <button class="btn btn-dark">Guardar evento</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Eventos publicados</h2>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Título</th><th>Fecha</th><th>Aforo</th><th>Acciones</th></tr></thead>
                            <tbody>
                            <?php if (empty($events)): ?>
                                <tr><td colspan="4" class="text-muted">No hay eventos creados todavía.</td></tr>
                            <?php else: foreach ($events as $event): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($event['title']) ?></strong>
                                        <div class="text-muted small"><?= esc($event['location']) ?></div>
                                    </td>
                                    <td><?= esc($event['start_date']) ?></td>
                                    <td><?= (int) $event['attendees'] ?> / <?= (int) $event['capacity'] ?></td>
                                    <td class="d-flex gap-2">
                                        <a href="view_event.php?id=<?= (int) $event['id'] ?>" class="btn btn-outline-primary btn-sm">Ver</a>
                                        <form method="post" onsubmit="return confirm('¿Eliminar evento?')">
                                            <input type="hidden" name="csrf_token" value="<?= esc(csrfToken()) ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int) $event['id'] ?>">
                                            <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php renderFooter(); ?>
