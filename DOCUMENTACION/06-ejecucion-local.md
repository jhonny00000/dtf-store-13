# Ejecución local

## 1. Entorno usado

- Directorio: `C:\laragon\www\dtf-store-13`
- Laragon con PHP 8.3.30, Composer 2.8.11 y NPM 10.9.2.
- Laravel 12.73.0.
- Base de datos prevista: MySQL `dtf_store_13` en `127.0.0.1:3306`.

## 2. Puesta en marcha

```bash
cd C:\laragon\www\dtf-store-13
composer install
php artisan serve
```

Abre `http://127.0.0.1:8000`.

También puedes usar el virtual host de Laragon apuntando a `public/` y mantener `APP_URL=http://localhost`.

## 3. Estado verificado de base de datos

- La configuración MySQL actual del repo usa `root` sin contraseña y devuelve `Access denied` en este entorno.
- No se modificó esa sección de `.env`.
- Las migraciones y seeders se ejecutaron correctamente contra SQLite local con:

```powershell
$env:DB_DATABASE='database/database.sqlite'
php artisan migrate --database=sqlite --force
php artisan db:seed --database=sqlite --force
```

- No se ejecutaron `migrate:fresh`, `migrate:refresh` ni `db:wipe`.
- Para MySQL, configura credenciales válidas en `.env`, crea la base de datos y ejecuta `php artisan migrate` y `php artisan db:seed`.

## 4. Comandos útiles

```bash
php artisan route:list
php artisan view:cache
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan test
```

## 5. Correo de recuperación

`MAIL_MAILER=log` está configurado. Los enlaces de restablecimiento se escriben en:

```text
storage/logs/laravel.log
```

## 6. Cambiar credenciales del administrador

Agrega o ajusta en `.env`:

```dotenv
ADMIN_EMAIL=admin@dtfstore13.test
ADMIN_PASSWORD=tu_contraseña_segura
```

Luego:

```bash
php artisan db:seed
```

El seeder actualiza la contraseña del administrador existente cuando `ADMIN_PASSWORD` está definido. Nunca dejes contraseñas hardcoded en el código.

## 7. Imágenes

Las imágenes de demostración son SVG locales en `public/images/productos`. Las subidas desde el panel se guardan en esa misma carpeta con nombre aleatorio. No se usa almacenamiento remoto.
