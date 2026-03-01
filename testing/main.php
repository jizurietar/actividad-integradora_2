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
//$venta = new Venta($conn);
//$ventas =  $venta->read();
//var_dump($ventas);
//$idventa = $venta->getVentasByProducto(2);
///var_dump($idventa);

//$producto = new Producto($conn); 
//$producto->id = 1;
//$row = $producto->readOne();
//var_dump($row);

//$productoController = new ProductoController();
//$row = $productoController->show(1000);
//echo $row;
//var_dump($row);


/*Ventas de producto

$ventaService = new VentaService($conn);
$totalVentas = $ventaService->getTotalVentas();
var_dump($totalVentas);


/*Probando los metodos de controladores*/

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/actividad-integradora_2/controllers/ProductoController.php?id=");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo $response;
}
?>
