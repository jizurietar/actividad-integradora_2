<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Sistema de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-box-seam"></i> Inventario & Ventas
            </a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-house"></i> Inicio
                </a>
                <a class="nav-link active" href="productos.php">
                    <i class="bi bi-box"></i> Productos
                </a>
                <a class="nav-link" href="ventas.php">
                    <i class="bi bi-cart"></i> Ventas
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-box"></i> Gestión de Productos</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productoModal">
                        <i class="bi bi-plus-circle"></i> Nuevo Producto
                    </button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="productosTable">
                                <thead>
                                    <tr>
                                       <th style="width: 60px;">ID</th>
                                        <th style="width: 200px;">Nombre</th>
                                        <th>Descripción</th>
                                        <th style="width: 80px; text-align: right;">Stock</th>
                                        <th style="width: 100px; text-align: right;">Precio</th>
                                        <th style="width: 120px;">Fecha Creación</th>
                                        <th style="width: 120px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productosTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Producto -->
    <div class="modal fade" id="productoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="productoForm">
                        <input type="hidden" id="productoId">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre" required>
                            <div class="form-text">El nombre no puede estar vacío</div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" rows="3" required></textarea>
                            <div class="form-text">La descripción no puede estar vacía</div>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" min="0" required>
                            <div class="form-text">El stock debe ser mayor o igual a 0</div>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="precio" min="0.01" step="0.01" required>
                            <div class="form-text">El precio debe ser mayor a 0</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveProducto()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let editingProducto = false;

        function loadProductos() {
            fetch('../controllers/ProductoController.php')
                .then(response => response.json())
                .then(productos => {
                    const tbody = document.getElementById('productosTableBody');
                    tbody.innerHTML = '';
                    
                    productos.forEach(producto => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td >${producto.descripcion ?? ''}</td>
                            <td style="text-align: right;">
                                <span class="badge ${producto.stock < 5 ? 'bg-danger' : 'bg-success'}">
                                    ${producto.stock}
                                </span>
                            </td>
                            <td style="text-align: right;">$${parseFloat(producto.precio).toFixed(2)}</td>
                            <td>${new Date(producto.fecha_creacion).toLocaleDateString()}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editProducto(${producto.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteProducto(${producto.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al cargar los productos', 'danger');
                });
        }

        function saveProducto() {
            const id = document.getElementById('productoId').value;
            const nombre = document.getElementById('nombre').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            const stock = parseInt(document.getElementById('stock').value);
            const precio = parseFloat(document.getElementById('precio').value);

            if (!nombre) {
                showAlert('El nombre del producto no puede estar vacío', 'danger');
                return;
            }

            if (!descripcion) {
                showAlert('La descripción del producto no puede estar vacía', 'danger');
                return;
            }

            if (isNaN(stock) || stock < 0) {
                showAlert('El stock debe ser un número mayor o igual a 0', 'danger');
                return;
            }

            if (isNaN(precio) || precio <= 0) {
                showAlert('El precio debe ser un número mayor a 0', 'danger');
                return;
            }

            const data = { nombre, descripcion, stock, precio };
            const url = id ? `../controllers/ProductoController.php?id=${id}` : '../controllers/ProductoController.php';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showAlert(id ? 'Producto actualizado correctamente' : 'Producto creado correctamente', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('productoModal')).hide();
                    loadProductos();
                    resetForm();
                } else {
                    showAlert(result.errors.join(', '), 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al guardar el producto', 'danger');
            });
        }

        function editProducto(id) {
            fetch(`../controllers/ProductoController.php?id=${id}`)
                .then(response => response.json())
                .then(producto => {
                    if (producto.success === false) {
                        showAlert(producto.errors.join(', '), 'danger');
                        return;
                    }
                    
                    document.getElementById('productoId').value = producto.id;
                    document.getElementById('nombre').value = producto.nombre;
                     document.getElementById('descripcion').value = producto.descripcion;
                    document.getElementById('stock').value = producto.stock;
                    document.getElementById('precio').value = producto.precio;
                    document.getElementById('modalTitle').textContent = 'Editar Producto';
                    editingProducto = true;
                    
                    new bootstrap.Modal(document.getElementById('productoModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al cargar el producto', 'danger');
                });
        }

        function deleteProducto(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                fetch(`../controllers/ProductoController.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showAlert('Producto eliminado correctamente', 'success');
                        loadProductos();
                    } else {
                        showAlert(result.errors.join(', '), 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al eliminar el producto', 'danger');
                });
            }
        }

        function resetForm() {
            document.getElementById('productoForm').reset();
            document.getElementById('productoId').value = '';
            document.getElementById('modalTitle').textContent = 'Nuevo Producto';
            editingProducto = false;
        }

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('.container');
            container.insertBefore(alertDiv, container.firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        document.getElementById('productoModal').addEventListener('hidden.bs.modal', function () {
            resetForm();
        });

        loadProductos();
    </script>
</body>
</html>
