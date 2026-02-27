<?php

require_once '../config/database.php';
require_once '../models/Venta.php';
require_once '../models/Producto.php';
require_once '../services/VentaService.php';


$db = new Database();
$conn = $db->getConnection();

$venta = new Venta($conn);
$producto = new Producto($conn); 

$ventas =  $venta->read();
//var_dump($ventas);

$idventa = $venta->getVentasByProducto(2);
var_dump($idventa);

$productos = $producto->read();
//var_dump($productos);

/*Ventas de producto*/

$ventaService = new VentaService($conn);
$totalVentas = $ventaService->getTotalVentas();
var_dump($totalVentas);
?>