<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas - Sistema de Inventario</title>
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
                <a class="nav-link" href="productos.php">
                    <i class="bi bi-box"></i> Productos
                </a>
                <a class="nav-link active" href="ventas.php">
                    <i class="bi bi-cart"></i> Ventas
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-cart"></i> Gestión de Ventas</h2>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ventaModal">
                        <i class="bi bi-plus-circle"></i> Nueva Venta
                    </button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="ventasTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th style="text-align: right;">Cantidad</th>
                                        <th style="text-align: right;">Precio Unitario</th>
                                        <th style="text-align: right;">Total</th>
                                        <th style="text-align: right;">Fecha Venta</th>
                                    </tr>
                                </thead>
                                <tbody id="ventasTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Venta -->
    <div class="modal fade" id="ventaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ventaForm">
                        <div class="mb-3">
                            <label for="producto_id" class="form-label">Producto</label>
                            <select class="form-select" id="producto_id" required>
                                <option value="">Selecciona un producto...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" min="1" required>
                            <div class="form-text">La cantidad debe ser mayor a 0</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock Disponible</label>
                            <input type="text" class="form-control" id="stock_disponible" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio Unitario</label>
                            <input type="text" class="form-control" id="precio_unitario" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total</label>
                            <input type="text" class="form-control" id="total" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="saveVenta()">Registrar Venta</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let productos = [];

        function loadProductos() {
            fetch('../controllers/ProductoController.php')
                .then(response => response.json())
                .then(data => {
                    productos = data;
                    const select = document.getElementById('producto_id');
                    select.innerHTML = '<option value="">Selecciona un producto...</option>';
                    
                    productos.forEach(producto => {
                        const option = document.createElement('option');
                        option.value = producto.id;
                        option.textContent = `${producto.nombre} (Stock: ${producto.stock})`;
                        option.dataset.stock = producto.stock;
                        option.dataset.precio = producto.precio;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al cargar los productos', 'danger');
                });
        }

        function loadVentas() {
            fetch('../controllers/VentaController.php')
                .then(response => response.json())
                .then(ventas => {
                    const tbody = document.getElementById('ventasTableBody');
                    tbody.innerHTML = '';
                    
                    ventas.forEach(venta => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${venta.id}</td>
                            <td>${venta.producto_nombre || 'Producto #' + venta.producto_id}</td>
                            <td style="text-align: right;">${venta.cantidad}</td>
                            <td style="text-align: right;">$${parseFloat(venta.precio_unitario).toFixed(2)}</td>
                            <td style="text-align: right;">$${parseFloat(venta.total).toFixed(2)}</td>
                            <td style="text-align: right;">${new Date(venta.fecha_venta).toLocaleDateString()}</td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al cargar las ventas', 'danger');
                });
        }

        document.getElementById('producto_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stock = selectedOption.dataset.stock || 0;
            const precio = selectedOption.dataset.precio || 0;
            
            document.getElementById('stock_disponible').value = stock;
            document.getElementById('precio_unitario').value = '$' + parseFloat(precio).toFixed(2);
            
            const cantidad = document.getElementById('cantidad').value || 0;
            updateTotal(precio, cantidad);
        });

        document.getElementById('cantidad').addEventListener('input', function() {
            const precio = document.getElementById('precio_unitario').value.replace('$', '') || 0;
            updateTotal(precio, this.value);
        });

        function updateTotal(precio, cantidad) {
            const total = parseFloat(precio) * parseInt(cantidad) || 0;
            document.getElementById('total').value = '$' + total.toFixed(2);
        }

        function saveVenta() {
            const producto_id = document.getElementById('producto_id').value;
            const cantidad = parseInt(document.getElementById('cantidad').value);

            if (!producto_id) {
                showAlert('Debes seleccionar un producto', 'danger');
                return;
            }

            if (!cantidad || cantidad <= 0) {
                showAlert('La cantidad debe ser mayor a 0', 'danger');
                return;
            }

            const selectedOption = document.getElementById('producto_id').options[document.getElementById('producto_id').selectedIndex];
            const stockDisponible = parseInt(selectedOption.dataset.stock);

            if (cantidad > stockDisponible) {
                showAlert(`Stock insuficiente. Stock disponible: ${stockDisponible}`, 'danger');
                return;
            }

            const data = { producto_id, cantidad };

            fetch('../controllers/VentaController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showAlert('Venta registrada correctamente', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('ventaModal')).hide();
                    loadVentas();
                    loadProductos();
                    resetForm();
                } else {
                    showAlert(result.errors.join(', '), 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al registrar la venta', 'danger');
            });
        }

        function resetForm() {
            document.getElementById('ventaForm').reset();
            document.getElementById('stock_disponible').value = '';
            document.getElementById('precio_unitario').value = '';
            document.getElementById('total').value = '';
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

        document.getElementById('ventaModal').addEventListener('hidden.bs.modal', function () {
            resetForm();
        });

        loadProductos();
        loadVentas();
    </script>
</body>
</html>
