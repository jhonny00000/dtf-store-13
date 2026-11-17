# Pruebas

## 1. Configuración

`phpunit.xml` fuerza una base SQLite en memoria, por lo que las pruebas no tocan MySQL ni datos reales:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

Cada prueba usa `RefreshDatabase`, ejecuta migraciones desde cero y aplica seeders cuando los necesita.

## 2. Ejecución

```bash
php artisan test
```

También:

```bash
vendor/bin/phpunit
```

## 3. Resultado verificado

```text
PASS  Tests\Unit\ExampleTest
PASS  Tests\Feature\DatosInvalidosTest
PASS  Tests\Feature\ExampleTest
PASS  Tests\Feature\ProductoRegistroTest
PASS  Tests\Feature\RutasYVistasTest
PASS  Tests\Feature\SeguridadTest

Tests:    10 passed (73 assertions)
```

## 4. Casos cubiertos

### ProductoRegistroTest

- Registro válido de producto con categoria, tallas, colores, imagen, variante, SKU y stock inicial.
- Verifica creación en `productos`, `producto_variantes` e `inventarios`.

### DatosInvalidosTest

- Rechazo de campos obligatorios vacíos, precios negativos, stock negativo y archivos inválidos.
- Rechazo de categoría inexistente sin persistir el producto.
- Rechazo de archivos que no son imágenes.

### SeguridadTest

- Invitado no puede acceder al panel.
- Invitado no puede registrar productos.
- Cliente autenticado no puede acceder al panel.

### ExampleTest (Unit y Feature)

- Prueba unitaria base.
- La ruta `/` devuelve HTTP 200 con base migrada.

### RutasYVistasTest

- Carga de inicio, catálogo, detalle, carrito, login, registro y recuperación.
- Registro de cliente, alta al carrito, checkout y creación de pedido.
- Verificación de copia histórica del producto en el detalle.
- Verificación de descuento de stock en inventario.
- Carga de éxito, historial y detalle del pedido.
- Carga de las vistas principales del panel: dashboard, productos, categorías, inventario, clientes, pedidos y reportes.

## 5. Correcciones técnicas aplicadas durante la verificación

- Se eliminó una migración duplicada de `password_reset_tokens`; la migración base de Laravel ya crea esa tabla.
- Se agregó `RefreshDatabase` al `ExampleTest` Feature original.
- `Color` declaró explícitamente la tabla `colores`.
- La FK `color_id` de `producto_variantes` referencia explícitamente `colores`.
- Se agregó actualización de contraseña admin desde `ADMIN_PASSWORD` al volver a sembrar.

## 6. Verificación adicional realizada

- `php artisan migrate --database=sqlite --force`: 12 migraciones aplicadas correctamente.
- `php artisan db:seed --database=sqlite --force`: seeders completados correctamente.
- `php artisan view:cache`: plantillas Blade compiladas sin errores.
- `php artisan view:clear`: caché de vistas limpiada.
- `php artisan route:list`: rutas registradas correctamente.
