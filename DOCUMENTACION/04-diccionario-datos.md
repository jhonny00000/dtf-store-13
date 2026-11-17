# Diccionario de datos

Basado en las migraciones reales de `database/migrations`.

## users

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador del usuario |
| name | varchar | NOT NULL | Nombre visible |
| email | varchar | UNIQUE | Correo e inicio de sesión |
| role | varchar | DEFAULT `cliente`, INDEX | `cliente` o `admin` |
| telefono | varchar | NULL | Teléfono opcional |
| email_verified_at | datetime | NULL | Verificación de correo |
| password | varchar | NOT NULL | Contraseña cifrada |
| rememberToken | varchar | NULL | Token de sesión recordada |
| created_at / updated_at | datetime | — | Auditoría |

## clientes

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| user_id | bigint unsigned | FK users, NULL, UNIQUE, CASCADE | Usuario asociado |
| nombre | varchar | NOT NULL, INDEX | Nombre |
| apellidos | varchar | NULL | Apellidos |
| telefono | varchar | NULL | Teléfono |
| direccion | varchar | NULL | Dirección principal |
| ci | varchar | NULL | Carnet de identidad |
| created_at / updated_at | datetime | — | Auditoría |

## categorias

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| nombre | varchar | UNIQUE | Nombre de la categoría |
| slug | varchar | UNIQUE | Slug público |
| descripcion | text | NULL | Descripción |
| activo | boolean | DEFAULT true | Visibilidad |
| created_at / updated_at | datetime | — | Auditoría |

## productos

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| categoria_id | bigint unsigned | FK categorias, RESTRICT, INDEX | Categoría |
| nombre | varchar | NOT NULL | Nombre de la prenda |
| slug | varchar | UNIQUE | Slug de URL |
| descripcion | text | NOT NULL | Descripción |
| precio | decimal(10,2) | NOT NULL | Precio base en Bs |
| imagen | varchar | DEFAULT `images/productos/default.svg` | Imagen principal |
| imagenes | json | NULL | Hasta 4 rutas de galería |
| codigo | varchar | NULL, UNIQUE | SKU del producto |
| activo | boolean | DEFAULT true, INDEX | Publicación |
| destacado | boolean | DEFAULT false, INDEX | Producto destacado |
| created_at / updated_at | datetime | — | Auditoría |

## tallas

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| nombre | varchar | UNIQUE | Ej. `S`, `M`, `XL` |
| orden | unsignedInteger | DEFAULT 0 | Orden de visualización |
| created_at / updated_at | datetime | — | Auditoría |

## colores

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| nombre | varchar | UNIQUE | Ej. `Negro` |
| codigo | varchar(7) | DEFAULT `#000000` | Hexadecimal |
| created_at / updated_at | datetime | — | Auditoría |

> El modelo `Color` declara explícitamente `protected $table = 'colores'` porque la convención inglesa generaría `colors`.

## producto_variantes

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| producto_id | bigint unsigned | FK productos, CASCADE, INDEX | Producto padre |
| talla_id | bigint unsigned | FK tallas, RESTRICT | Talla |
| color_id | bigint unsigned | FK colores, RESTRICT | Color |
| precio_adicional | decimal(10,2) | DEFAULT 0 | Recargo por variante |
| activo | boolean | DEFAULT true | Disponibilidad de la variante |
| created_at / updated_at | datetime | — | Auditoría |

Índice único: `(producto_id, talla_id, color_id)` con nombre `variante_unica`. La FK `color_id` referencia explícitamente `colores`.

## inventarios

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| producto_variante_id | bigint unsigned | FK producto_variantes, UNIQUE, CASCADE | Variante controlada |
| stock | integer | DEFAULT 0, INDEX | Existencias |
| stock_minimo | integer | DEFAULT 5 | Umbral de alerta |
| created_at / updated_at | datetime | — | Auditoría |

## movimientos_inventario

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| inventario_id | bigint unsigned | FK inventarios, CASCADE, INDEX | Inventario afectado |
| user_id | bigint unsigned | FK users, NULL, SET NULL | Usuario responsable |
| tipo | varchar(20) | INDEX | `entrada`, `salida`, `venta`, `ajuste` |
| cantidad | integer | NOT NULL | Cantidad con signo |
| stock_anterior | integer | NOT NULL | Stock previo |
| stock_nuevo | integer | NOT NULL | Stock posterior |
| motivo | varchar | NULL | Descripción |
| referencia | varchar | NULL | Referencia opcional |
| created_at / updated_at | datetime | — | Auditoría |

## pedidos

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| numero | varchar | UNIQUE | Número legible `DTF-...` |
| user_id | bigint unsigned | FK users, NULL, SET NULL, INDEX | Comprador |
| cliente_id | bigint unsigned | FK clientes, NULL, SET NULL | Perfil del comprador |
| direccion_entrega | text | NOT NULL | Dirección de entrega |
| metodo_pago | varchar(20) | DEFAULT `efectivo` | `efectivo`, `transferencia`, `qr` |
| estado | varchar(20) | DEFAULT `pendiente`, INDEX | `pendiente`, `procesando`, `enviado`, `entregado`, `cancelado` |
| subtotal | decimal(10,2) | NOT NULL | Subtotal |
| costo_envio | decimal(10,2) | DEFAULT 0 | Costo de envío |
| total | decimal(10,2) | NOT NULL | Total |
| notas | text | NULL | Notas del cliente |
| fecha_pedido | timestamp | DEFAULT CURRENT_TIMESTAMP | Fecha de compra |
| created_at / updated_at | datetime | — | Auditoría |

Índice compuesto: `(estado, created_at)`.

## detalle_pedidos

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| pedido_id | bigint unsigned | FK pedidos, CASCADE, INDEX | Pedido |
| producto_id | bigint unsigned | FK productos, NULL, SET NULL | Producto original |
| producto_variante_id | bigint unsigned | FK producto_variantes, NULL, SET NULL | Variante original |
| nombre_producto | varchar | NOT NULL | Copia histórica del nombre |
| talla_nombre | varchar | NOT NULL | Copia histórica de la talla |
| color_nombre | varchar | NOT NULL | Copia histórica del color |
| imagen | varchar | NULL | Copia histórica de la imagen |
| cantidad | unsignedInteger | NOT NULL | Unidades |
| precio_unitario | decimal(10,2) | NOT NULL | Precio con recargo de variante |
| subtotal | decimal(10,2) | NOT NULL | `cantidad * precio_unitario` |
| created_at / updated_at | datetime | — | Auditoría |

## pagos

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | bigint unsigned | PK | Identificador |
| pedido_id | bigint unsigned | FK pedidos, CASCADE | Pedido asociado |
| metodo | varchar(20) | NOT NULL | Método elegido |
| estado | varchar(20) | DEFAULT `pendiente`, INDEX | `pendiente`, `pagado`, `rechazado`, `reembolsado` |
| monto | decimal(10,2) | NOT NULL | Monto registrado |
| referencia | varchar | NULL | Referencia externa opcional |
| fecha_pago | timestamp | NULL | Fecha de pago confirmado |
| created_at / updated_at | datetime | — | Auditoría |

## password_reset_tokens

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| email | varchar | PK | Correo solicitante |
| token | varchar | NOT NULL | Token de restablecimiento |
| created_at | datetime | NULL | Creación |

## sessions

| Campo | Tipo | Restricción | Descripción |
|---|---|---|---|
| id | varchar | PK | ID de sesión |
| user_id | bigint unsigned | FK users, NULL, INDEX | Usuario autenticado |
| ip_address | varchar(45) | NULL | IP |
| user_agent | text | NULL | Agente |
| payload | longText | NOT NULL | Datos de sesión |
| last_activity | integer | INDEX | Última actividad |
