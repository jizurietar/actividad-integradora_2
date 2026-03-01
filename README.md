# Sistema de Inventario y Ventas

Un sistema básico de gestión de inventario y ventas desarrollado en PHP con MySQL y Bootstrap.

## Características

## Módulo de Productos

- ✅ **Crear productos** con validaciones
- ✅ **Listar productos** con indicadores de stock
- ✅ **Editar productos** con validaciones
- ✅ **Eliminar productos** con confirmación

## Validaciones de Productos

- ✅ **Nombre no vacío**: El nombre del producto es obligatorio
- ✅ **Stock >= 0**: No permite stock negativo
- ✅ **Precio > 0**: El precio debe ser mayor a cero
- ✅ **No permitir stock negativo**: Validación en todas las operaciones

## Módulo de Ventas

- ✅ **Registrar ventas** con actualización automática de stock
- ✅ **Validación de stock disponible** antes de vender
- ✅ **Historial de ventas** con detalles completos
- ✅ **Cálculo automático** de totales

## Estructura del Proyecto

actividad_integradora_2/
├── config/
│   └── database.php          # Configuración de la base de datos
├── models/
│   ├── Producto.php          # Modelo de Producto con validaciones
│   └── Venta.php             # Modelo de Venta
├── controllers/
│   ├── ProductoController.php # Controlador de Productos (CRUD)
│   └── VentaController.php   # Controlador de Ventas
├── services/
│   └── VentaService.php      # Lógica de negocio para ventas
├── public/
│   ├── index.php            # Página principal con estadísticas
│   ├── productos.php        # Interfaz de gestión de productos
│   └── ventas.php           # Interfaz de gestión de ventas
├── database/
│   └── inventario.sql       # Script de base de datos
└── README.md                # Este archivo

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache, Nginx, etc.)
- Extensión PDO para MySQL

### Instalación

1. **Clonar o descargar el proyecto** en tu directorio web

2. **Crear la base de datos**:

   ```sql
   mysql -u root -p < database/inventario.sql
   ```

3. **Configurar la base de datos** (si es necesario):
   Editar `config/database.php` con tus credenciales de MySQL

4. **Configurar el servidor web**:
   - Asegúrate que el directorio `public` sea el document root
   - O configura un virtual host apuntando a `public/`

#### Uso

1. **Acceder al sistema**:

    ```
    http://localhost/actividad-integradora_2/public/
    ```

2. **Navegación**:
   - **Inicio**: Dashboard con estadísticas generales
   - **Productos**: Gestión completa del catálogo
   - **Ventas**: Registro y historial de ventas

##### Funcionalidades Detalladas

##### Gestión de Productos

- **Alta**: Formulario con validaciones en tiempo real
- **Modificación**: Edición de datos existentes
- **Baja**: Eliminación con confirmación
- **Consulta**: Listado con indicadores visuales de stock bajo

#### Gestión de Ventas

- **Registro**: Selección de producto y cantidad
- **Validación**: Verificación automática de stock disponible
- **Actualización**: Reducción automática del inventario
- **Historial**: Listado completo de transacciones

#### Validaciones Implementadas

- **Nombre**: Campo obligatorio, no vacío
- **Stock**: Valor numérico >= 0
- **Precio**: Valor numérico > 0
- **Stock Negativo**: Prevención en todas las operaciones y por medio de un trigger antes de la insercion en la tabla de ventas
- **Testing**: Donde se creo un archivo para probar modelos, controller y url

## Tecnologías Utilizadas

- **Backend**: PHP 8.3.6
- **Base de Datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework UI**: Bootstrap 5.3
- **Iconos**: Bootstrap Icons
- **Arquitectura**: MVC (Model-View-Controller)

## Notas Importantes

- El sistema es simple y educativo, ideal para aprender MVC en PHP
- Las validaciones se implementan tanto en frontend como en backend
- El stock se actualiza automáticamente en cada venta
- Los productos con stock bajo (< 5 unidades) se muestran en rojo

## Mejoras Futuras

- [ ] Sistema de usuarios y autenticación
- [ ] Reportes y gráficos avanzados
- [ ] Búsqueda y filtrado de productos
- [ ] Categorías de productos
- [ ] Gestión de proveedores
- [ ] Sistema de facturación

