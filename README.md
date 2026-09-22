# Campus Connect

MVP universitario para centralizar solicitudes de soporte, infraestructura, mantenimiento y equipamiento. El repositorio contiene el backend Laravel 12, el panel administrativo Blade y la API REST que utilizará la aplicación Flutter.

La implementación Web está en [`Web/`](Web/README.md).

## Credenciales demo

- Administrativo: `admin@campus.test` / `password`
- Estudiante (API): `estudiante@campus.test` / `password`

## Verificación rápida

```bash
cd Web
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Para levantar PostgreSQL localmente se incluye `Web/docker-compose.yml`. Las pruebas utilizan SQLite en memoria para ejecutarse con rapidez y sin servicios externos:

```bash
cd Web
php artisan test
```
