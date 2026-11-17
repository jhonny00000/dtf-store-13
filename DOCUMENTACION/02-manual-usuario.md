# Manual de usuario

## 1. Cliente

### Registro e ingreso

1. Entra a `/registro`.
2. Completa nombre, correo, contraseña y, opcionalmente, teléfono y dirección.
3. Inicia sesión desde `/login`.
4. Cierra sesión con el botón **Salir**.

La contraseña se almacena cifrada. Si la olvidas, usa **¿Olvidaste tu contraseña?**. En local el enlace de recuperación queda en `storage/logs/laravel.log` porque el correo está configurado en modo `log`.

### Catálogo

- Filtra por categoría, texto, orden por precio/nombre y disponibilidad.
- Abre una prenda para ver galería, descripción, precio, tallas, colores y stock.
- Selecciona talla y color; el sistema muestra el stock de esa combinación y deshabilita el botón si no hay existencias.

### Carrito

- Agrega productos desde el detalle.
- Cambia cantidades o elimina items desde `/carrito`.
- El sistema valida el stock disponible y calcula subtotal, envío y total.
- El envío cuesta Bs 15 y es gratis desde Bs 300.

### Pedidos

1. Con sesión iniciada, pulsa **Confirmar compra** en el carrito.
2. Ingresa dirección de entrega, método de pago (efectivo, transferencia o QR) y notas opcionales.
3. Registra el pedido. No se procesa ningún pago bancario real: el pago queda como **Pendiente**.
4. El sistema muestra el número de pedido y descuenta el inventario en una transacción.
5. Consulta tu historial y el detalle en **Mis pedidos**.

## 2. Administrador

Accede a `/admin` con una cuenta de rol `admin`.

### Dashboard

Muestra productos, clientes, pedidos, ventas, pedidos pendientes, productos con bajo stock, ventas mensuales y últimos pedidos.

### Productos

- Crea, lista, consulta, edita y elimina productos.
- Sube imagen principal y hasta 4 imágenes de galería (`jpg`, `jpeg`, `png`, `webp`, máx. 2 MB).
- Administra precio, SKU, descripción, destacado, activación e inicialización de stock por variantes.
- Activa o desactiva productos desde el listado.

### Categorías

CRUD completo. No se puede eliminar una categoría que todavía tenga productos asociados.

### Inventario

- Consulta existencias por talla/color.
- Ajusta stock, stock mínimo, precio adicional y estado de la variante.
- Registra entradas y salidas manuales.
- Consulta el historial de movimientos, incluidas las ventas y devoluciones por cancelación.

### Clientes

Registra, lista, consulta y edita clientes. El alta crea también su usuario con rol `cliente`.

### Pedidos y ventas

- Lista por estado y rango de fechas.
- Consulta detalle, productos, dirección y pago.
- Actualiza estado del pedido y estado del pago.
- Al cancelar un pedido, el stock se devuelve automáticamente. Un pedido cancelado no se puede reactivar desde el panel.

### Reportes

Genera reportes de ventas, productos más vendidos, pedidos por estado e inventario con bajo stock, filtrando por fechas.
