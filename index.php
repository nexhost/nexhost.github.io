<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$stmt = $pdo->query('SELECT e.*, u.name AS organizer FROM events e JOIN users u ON u.id = e.user_id WHERE e.start_date >= CURDATE() ORDER BY e.start_date ASC LIMIT 6');
$events = $stmt->fetchAll();

renderHeader('Plataforma de Eventos');
?>
<section class="bg-gradient-to-r from-slate-900 to-indigo-900 text-white py-5 mb-5">
    <div class="container py-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold mb-3">Organiza eventos memorables con una experiencia tipo Eventtia</h1>
                <p class="lead text-slate-200">Crea eventos, gestiona asistentes, publica agenda y administra registros desde un panel moderno con PHP + MySQL + Bootstrap + Tailwind.</p>
                <div class="d-flex gap-2 mt-4">
                    <a href="register.php" class="btn btn-primary btn-lg">Comenzar gratis</a>
                    <a href="events.php" class="btn btn-outline-light btn-lg">Ver demo</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="glass-card p-4 rounded-4">
                    <h5 class="mb-3">Qué incluye el sistema</h5>
                    <ul class="list-unstyled mb-0 d-grid gap-2">
                        <li>✅ Gestión de eventos y registro online</li>
                        <li>✅ Dashboard con métricas en tiempo real</li>
                        <li>✅ Landing pública para captar asistentes</li>
                        <li>✅ Arquitectura lista para escalar módulos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Próximos eventos</h2>
        <a href="register.php" class="btn btn-dark btn-sm">Publicar evento</a>
    </div>
    <div class="row g-4">
        <?php if (empty($events)): ?>
            <div class="col-12"><div class="alert alert-info">Todavía no hay eventos publicados. Sé el primero en crear uno.</div></div>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card event-card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-light text-dark mb-3"><?= esc($event['location']) ?></span>
                            <h3 class="h5"><?= esc($event['title']) ?></h3>
                            <p class="text-muted small flex-grow-1"><?= esc(substr($event['description'], 0, 120)) ?>...</p>
                            <p class="mb-1"><strong>Fecha:</strong> <?= esc($event['start_date']) ?></p>
                            <p class="mb-3"><strong>Organiza:</strong> <?= esc($event['organizer']) ?></p>
                            <a class="btn btn-outline-primary mt-auto" href="view_event.php?id=<?= (int) $event['id'] ?>">Ver evento</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?php renderFooter(); ?>
