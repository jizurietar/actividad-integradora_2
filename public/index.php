<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario y Ventas</title>
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
                <a class="nav-link" href="ventas.php">
                    <i class="bi bi-cart"></i> Ventas
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron bg-primary text-white p-5 rounded">
                    <h1 class="display-4">Sistema de Inventario y Ventas</h1>
                    <p class="lead">Gestiona tu inventario y controla tus ventas de manera sencilla y eficiente.</p>
                    <hr class="my-4">
                    <p>Utiliza las opciones del menú para administrar productos y registrar ventas.</p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-box display-4 text-primary"></i>
                        <h5 class="card-title mt-3">Gestión de Productos</h5>
                        <p class="card-text">Administra tu catálogo de productos con control de stock y precios.</p>
                        <a href="productos.php" class="btn btn-primary">Ir a Productos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-cart-check display-4 text-success"></i>
                        <h5 class="card-title mt-3">Registro de Ventas</h5>
                        <p class="card-text">Registra ventas y actualiza el inventario automáticamente.</p>
                        <a href="ventas.php" class="btn btn-success">Ir a Ventas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-up display-4 text-info"></i>
                        <h5 class="card-title mt-3">Estadísticas</h5>
                        <p class="card-text">Visualiza el rendimiento de tu negocio en tiempo real.</p>
                        <button class="btn btn-info" onclick="loadEstadisticas()">Ver Estadísticas</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4" id="estadisticas" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-bar-chart"></i> Estadísticas del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h3 class="text-primary" id="totalProductos">-</h3>
                                    <p>Total Productos</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h3 class="text-success" id="totalVentas">-</h3>
                                    <p>Total Ventas</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h3 class="text-info" id="totalIngresos">-</h3>
                                    <p>Total Ingresos</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h3 class="text-warning" id="stockBajo">-</h3>
                                    <p>Stock Bajo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function loadEstadisticas() {
            const estadisticasDiv = document.getElementById('estadisticas');
            
            if (estadisticasDiv.style.display === 'none') {
                estadisticasDiv.style.display = 'block';
                
                fetch('../controllers/ProductoController.php')
                    .then(response => response.json())
                    .then(productos => {
                        document.getElementById('totalProductos').textContent = productos.length;
                        
                        const stockBajo = productos.filter(p => p.stock < 5).length;
                        document.getElementById('stockBajo').textContent = stockBajo;
                    })
                    .catch(error => console.error('Error:', error));

                fetch('../controllers/VentaController.php')
                    .then(response => response.json())
                    .then(ventas => {
                        document.getElementById('totalVentas').textContent = ventas.length;
                        
                        const totalIngresos = ventas.reduce((sum, venta) => sum + parseFloat(venta.total), 0);
                        document.getElementById('totalIngresos').textContent = '$' + totalIngresos.toFixed(2);
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                estadisticasDiv.style.display = 'none';
            }
        }
    </script>
</body>
</html>
