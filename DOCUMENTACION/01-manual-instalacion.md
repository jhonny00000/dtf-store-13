# Manual de instalación

## 1. Requisitos

- PHP 8.3 o superior (verificado con PHP 8.3.30).
- Laravel 12.73.0 (verificado en `composer.lock`).
- Composer 2.x.
- MySQL 8.4 en local o MariaDB en producción.
- Extensiones PHP: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`, `intl`.
- NPM es opcional: la interfaz usa CSS y JS propios copiados en `public/css` y `public/js`.

## 2. Obtener dependencias

```bash
composer install
```

Si se desea trabajar con Vite además de los archivos públicos ya incluidos:

```bash
npm install
npm run build
```

## 3. Configuración del entorno

El proyecto ya contiene un archivo `.env`. **No lo sobrescribas**. Verifica que conserva la sección de base de datos y agrega, si faltan, las claves de aplicación:

```dotenv
APP_NAME="DTF Store 13"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

SESSION_DRIVER=file
CACHE_STORE=file
MAIL_MAILER=log
```

Si `APP_KEY` está vacío, genera uno sin borrar el resto del archivo:

```bash
php artisan key:generate
```

Crea la base de datos local `dtf_store_13` en MySQL/MariaDB y ajusta en `.env` únicamente tus credenciales reales:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dtf_store_13
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

> En el entorno de desarrollo inicial, el usuario `root` sin contraseña devolvió `Access denied`. Por eso las migraciones y seeders se verificaron en SQLite local, sin modificar la configuración MySQL existente.

## 4. Migraciones y datos iniciales

**No ejecutar** `migrate:fresh`, `migrate:refresh` ni `db:wipe`.

```bash
php artisan migrate
php artisan db:seed
```

Antes de sembrar, puedes definir credenciales conocidas para el administrador en `.env`:

```dotenv
ADMIN_EMAIL=admin@dtfstore13.test
ADMIN_PASSWORD=tu_contraseña_segura
```

Si no defines `ADMIN_PASSWORD`, el seeder genera una contraseña aleatoria y la muestra una sola vez por consola. Si el administrador ya existe y luego defines `ADMIN_PASSWORD`, volver a ejecutar `php artisan db:seed` actualizará su contraseña.

El seeder carga categorías, tallas, colores, productos de demostración e imágenes SVG locales en `public/images/productos`.

## 5. Enlace de almacenamiento

No es obligatorio: las imágenes se suben directamente a `public/images/productos`. Si además deseas usar el disco `public` de Laravel:

```bash
php artisan storage:link
```

## 6. Verificación

```bash
php artisan about
php artisan route:list
php artisan test
```

## 7. Accesos

- Tienda: `http://localhost:8000/`
- Catálogo: `http://localhost:8000/catalogo`
- Panel: `http://localhost:8000/admin`
