# EventFlow Pro (PHP + MySQL + Bootstrap + Tailwind + JS)

Proyecto base completo para lanzar una plataforma de gestión de eventos inspirada en la experiencia de Eventtia.

## Stack
- PHP 8+
- MySQL 8+
- Bootstrap 5
- TailwindCSS (CDN)
- JavaScript

## Funcionalidades incluidas
- Registro e inicio de sesión de organizadores.
- Dashboard con métricas clave.
- CRUD de eventos (crear/listar/eliminar).
- Landing pública de eventos.
- Página de detalle y registro de asistentes.
- Protección CSRF en formularios principales.

## Estructura
- `index.php`: home pública.
- `register.php` / `login.php`: autenticación.
- `dashboard.php`: panel principal.
- `events.php`: gestión de eventos.
- `view_event.php`: detalle y registro de asistentes.
- `database/schema.sql`: base de datos.

## Instalación
1. Crear base de datos con:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
2. Configurar credenciales en `config/database.php` (o variables de entorno `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`).
3. Ejecutar un servidor local:
   ```bash
   php -S localhost:8080
   ```
4. Abrir `http://localhost:8080`.

## Próximos módulos recomendados
- Roles (admin/staff/speaker).
- Tickets y pasarelas de pago.
- QR check-in para asistentes.
- Módulo de agenda/speakers.
- Reportes exportables y API REST.
