# Arquitectura del sistema

## 1. Stack

- Laravel 12.73.0, PHP 8.3, Blade, MySQL 8.4 / MariaDB.
- CSS y JS propios (sin frameworks frontend obligatorios).
- Autenticación nativa de Laravel con sesiones en archivo.
- PHPUnit para pruebas Feature.

## 2. Capas

### Rutas

`routes/web.php` define:

- Rutas públicas: inicio, catálogo, detalle, carrito, autenticación, recuperación, checkout y pedidos del cliente.
- Rutas protegidas con `auth`: checkout, historial y detalle de pedidos, cierre de sesión.
- Rutas protegidas con `auth` + alias `admin`: `/admin` y todos sus módulos.

El alias `admin` se registra en `bootstrap/app.php` y apunta a `App\Http\Middleware\EnsureUserIsAdmin`.

### Controladores

- `App\Http\Controllers\AuthController` y `ForgotPasswordController`: autenticación.
- `App\Http\Controllers\Publico\HomeController`, `CatalogoController`, `CarritoController`, `PedidoController`: tienda.
- `App\Http\Controllers\Admin\DashboardController`, `ProductoController`, `CategoriaController`, `InventarioController`, `ClienteController`, `PedidoController`, `ReporteController`: panel.

### Servicios

- `App\Services\CarritoService`: carrito en sesión, recalcula precios desde la base de datos, valida stock, calcula envío y total.

### Modelos y relaciones

- `User` tiene un `Cliente`, muchos `Pedidos` y muchos `MovimientoInventario`.
- `Cliente` pertenece a `User` y tiene muchos `Pedidos`.
- `Categoria` tiene muchos `Productos`.
- `Producto` pertenece a `Categoria` y tiene muchas `ProductoVariante`.
- `ProductoVariante` pertenece a `Producto`, `Talla` y `Color`, y tiene un `Inventario`.
- `Inventario` pertenece a `ProductoVariante` y tiene muchos `MovimientoInventario`.
- `Pedido` pertenece a `User` y `Cliente`, y tiene muchos `DetallePedido` y un `Pago`.
- `DetallePedido` guarda una copia histórica del nombre, variante, imagen, precio unitario y subtotal.

### Vistas

- `resources/views/layouts/app.blade.php`: layout público.
- `resources/views/layouts/admin.blade.php`: layout del panel con sidebar.
- `resources/views/partials`: encabezado, pie y tarjeta de producto.
- `resources/views/home`, `catalogo`, `carrito`, `auth`, `pedidos`: tienda.
- `resources/views/admin`: panel.

Los estilos fuente están en `resources/css/app.css` y una copia lista para servir en `public/css/app.css`; el JS está en `resources/js/app.js` y `public/js/app.js`.

## 3. Seguridad

- Contraseñas cifradas con el cast `hashed` y `Hash::make`.
- CSRF en todos los formularios.
- Validación con `validate()` en cada acción.
- Middleware `auth` y `admin`; los clientes son redirigidos al login si intentan entrar al panel.
- Subida de imágenes validada por MIME y tamaño; se guardan con nombre aleatorio.
- Consultas con `lockForUpdate()` y actualizaciones condicionales de stock al registrar pedidos.
- Datos sensibles solo en `.env`, que no se versiona ni se sobrescribe.

## 4. Reglas de negocio

- El stock se administra por variante talla/color en `inventarios`.
- No se puede agregar al carrito ni confirmar un pedido por encima del stock.
- Los pedidos se crean dentro de `DB::transaction`, descontando stock y registrando movimientos tipo `venta`.
- El precio del detalle del pedido se copia al momento de la compra.
- El pago se registra como pendiente; no hay integración con pasarelas externas.
- Cancelar un pedido devuelve el stock y crea un movimiento de entrada.
