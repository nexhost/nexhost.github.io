<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';
requireAuth();

$userId = (int) $_SESSION['user_id'];

$stats = [
    'events' => 0,
    'registrations' => 0,
    'upcoming' => 0,
];

$stats['events'] = (int) $pdo->query("SELECT COUNT(*) FROM events WHERE user_id = {$userId}")->fetchColumn();
$stats['registrations'] = (int) $pdo->query("SELECT COUNT(r.id) FROM registrations r JOIN events e ON e.id = r.event_id WHERE e.user_id = {$userId}")->fetchColumn();
$stats['upcoming'] = (int) $pdo->query("SELECT COUNT(*) FROM events WHERE user_id = {$userId} AND start_date >= CURDATE()")->fetchColumn();

$recentEvents = $pdo->query("SELECT id, title, start_date, location FROM events WHERE user_id = {$userId} ORDER BY created_at DESC LIMIT 5")->fetchAll();

renderHeader('Dashboard');
?>
<div class="container py-5">
    <h1 class="h2 mb-4">Dashboard de organización</h1>
    <div class="row g-4 mb-4">
        <div class="col-md-4"><div class="card metric-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Eventos creados</p><h2 class="display-6 mb-0"><?= $stats['events'] ?></h2></div></div></div>
        <div class="col-md-4"><div class="card metric-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Registros recibidos</p><h2 class="display-6 mb-0"><?= $stats['registrations'] ?></h2></div></div></div>
        <div class="col-md-4"><div class="card metric-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Próximos eventos</p><h2 class="display-6 mb-0"><?= $stats['upcoming'] ?></h2></div></div></div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Últimos eventos</h2>
                <a class="btn btn-sm btn-dark" href="events.php">Gestionar</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Título</th><th>Fecha</th><th>Lugar</th><th></th></tr></thead>
                    <tbody>
                    <?php if (empty($recentEvents)): ?>
                        <tr><td colspan="4" class="text-muted">Todavía no tienes eventos.</td></tr>
                    <?php else: foreach ($recentEvents as $event): ?>
                        <tr>
                            <td><?= esc($event['title']) ?></td>
                            <td><?= esc($event['start_date']) ?></td>
                            <td><?= esc($event['location']) ?></td>
                            <td><a href="view_event.php?id=<?= (int) $event['id'] ?>" class="btn btn-outline-primary btn-sm">Ver</a></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php renderFooter(); ?>
