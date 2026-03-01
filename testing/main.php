<?php

require_once '../config/database.php';
require_once '../models/Venta.php';
require_once '../models/Producto.php';
require_once '../services/VentaService.php';
require_once '../controllers/ProductoController.php';
require_once '../controllers/VentaController.php';
/*Probando el sistema*/


$db = new Database();
$conn = $db->getConnection();

/*Busca un registro del model*/

/*$producto = new Producto($conn); 
$row = $producto->read();
var_dump($row);*/

/*$venta = new Venta($conn);
$ventas =  $venta->read();
var_dump($ventas);*/

/*Ventas de totales producto*/
/*
$ventaService = new VentaService($conn);
$totalVentas = $ventaService->getTotalVentas();
var_dump($totalVentas);
*/

/*Probando los metodos de controladores*/
/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/actividad-integradora_2/controllers/ProductoController.php?id=1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo $response;
}*/
   
?>
