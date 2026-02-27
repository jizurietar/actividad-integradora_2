<?php

require_once '../models/Venta.php';
require_once '../models/Producto.php';

class VentaService {
    private $db;
    private $venta;
    private $producto;

    public function __construct($db) {
        $this->db = $db;
        $this->venta = new Venta($db);
        $this->producto = new Producto($db);
    }

    public function procesarVenta($producto_id, $cantidad) {
        if ($cantidad <= 0) {
            return ['success' => false, 'errors' => ['La cantidad debe ser mayor a 0']];
        }

        $this->producto->id = $producto_id;
        if (!$this->producto->readOne()) {
            return ['success' => false, 'errors' => ['Producto no encontrado']];
        }

        if ($this->producto->stock < $cantidad) {
            return ['success' => false, 'errors' => ['Stock insuficiente. Stock disponible: ' . $this->producto->stock]];
        }

        $total = $this->producto->precio * $cantidad;

        $this->venta->producto_id = $producto_id;
        $this->venta->cantidad = $cantidad;
        $this->venta->precio_unitario = $this->producto->precio;
        $this->venta->total = $total;

        $result = $this->venta->create();
        if ($result['success']) {
            $stockResult = $this->producto->updateStock(-$cantidad);
            if (!$stockResult['success']) {
                return ['success' => false, 'errors' => ['Error al actualizar el stock: ' . implode(', ', $stockResult['errors'])]];
            }
            return ['success' => true, 'message' => 'Venta procesada correctamente', 'venta_id' => $result['id']];
        } else {
            return ['success' => false, 'errors' => $result['errors']];
        }
    }

    public function getVentasByProducto($producto_id) {
        return $this->venta->getVentasByProducto($producto_id);
    }

    public function getTotalVentas() {
        $query = "SELECT COUNT(*) as total_ventas, SUM(total) as total_dinero FROM ventas";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
