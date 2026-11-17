# Módulos implementados

| # | Módulo | Alcance | Archivos principales |
|---|---|---|---|
| 1 | Autenticación | Registro, login, logout, recuperación de contraseña por log | `AuthController`, `ForgotPasswordController`, `routes/web.php`, `resources/views/auth` |
| 2 | Roles y permisos | `cliente` y `admin`, middleware `admin`, redirección segura | `EnsureUserIsAdmin`, `bootstrap/app.php`, `User` |
| 3 | Catálogo público | Listado, búsqueda, filtros por categoría, orden, disponibilidad y detalle con galería | `CatalogoController`, `resources/views/catalogo` |
| 4 | Carrito | Sesión, alta/cantidades/eliminación, validación de stock, cálculo de envío | `CarritoService`, `CarritoController`, `resources/views/carrito` |
| 5 | Pedidos del cliente | Checkout, confirmación transaccional, historial y detalle | `PedidoController`, `resources/views/pedidos` |
| 6 | Inventario transaccional | Bloqueo de fila, descuento por venta, movimientos y devolución al cancelar | `PedidoController`, `InventarioController`, `MovimientoInventario` |
| 7 | Productos admin | CRUD, imágenes, variantes, destacado, activación y stock inicial | `Admin\ProductoController`, `resources/views/admin/productos` |
| 8 | Categorías admin | CRUD y protección contra borrado con productos asociados | `Admin\CategoriaController`, `resources/views/admin/categorias` |
| 9 | Inventario admin | Ajustes manuales, entradas/salidas, umbrales e historial | `Admin\InventarioController`, `resources/views/admin/inventario` |
| 10 | Clientes admin | CRUD, creación de usuario con rol cliente y consistencia de perfil | `Admin\ClienteController`, `resources/views/admin/clientes` |
| 11 | Pedidos admin | Listado por estado, detalle, cambio de estado y cancelación con reversión de stock | `Admin\PedidoController`, `resources/views/admin/pedidos` |
| 12 | Pagos | Registro por pedido, estado y referencia opcional; sin pasarela real | `Pago`, `PedidoController` |
| 13 | Dashboard | Indicadores, ventas mensuales, alertas y últimos pedidos | `Admin\DashboardController`, `resources/views/admin/dashboard` |
| 14 | Reportes | Ventas por rango de fechas, ticket promedio, top productos, estados y bajo stock | `Admin\ReporteController`, `resources/views/admin/reportes` |
| 15 | Diseño responsive | Tema negro/blanco/mostaza, menú móvil, sidebar admin, tablas y formularios | `resources/css/app.css`, `public/css/app.css` |
| 16 | Interacciones JS | Menú, confirmaciones, galería, preview, cantidades y filtros | `resources/js/app.js`, `public/js/app.js` |
| 17 | Seeders | Admin, 4 categorías, 5 tallas, 4 colores y 9 productos con 20 variantes cada uno (180 variantes) e inventarios | `database/seeders` |
| 18 | Pruebas | 10 tests Feature/Unit, 73 aserciones, flujo de compra y vistas | `tests/Feature`, `tests/Unit`, `phpunit.xml` |

## Matriz de acceso

| Ruta | Invitado | Cliente | Admin |
|---|---|---|---|
| `/`, `/catalogo`, `/carrito` | Sí | Sí | Sí |
| `/login`, `/registro`, `/olvide-password` | Sí | Redirigido | Redirigido |
| `/checkout`, `/mis-pedidos` | No | Sí | Sí |
| `/admin/*` | No | No | Sí |

## Flujo de compra

1. El cliente selecciona variante y cantidad.
2. `CarritoService` consulta precio e inventario vigentes.
3. En checkout se validan dirección y método de pago.
4. `PedidoController@store` abre transacción, bloquea el inventario, verifica stock, descuenta unidades, crea `DetallePedido`, crea `Pago` pendiente y registra movimiento tipo `venta`.
5. Si falla cualquier validación, se revierte toda la transacción.
