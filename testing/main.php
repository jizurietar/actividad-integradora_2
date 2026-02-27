<?php

require_once '../config/database.php';
require_once '../models/Venta.php';
require_once '../models/Producto.php';
require_once '../services/VentaService.php';

/*Probando el sistema*/

/*
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

/*Ventas de producto

$ventaService = new VentaService($conn);
$totalVentas = $ventaService->getTotalVentas();
var_dump($totalVentas);

*/
/*Probando los metodos de controladores*/

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/actividad-integradora_2/controllers/ProductoController.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo $response;
}
?>