<?php

class Venta {
    private $conn;
    private $table_name = "ventas";

    public $id;
    public $producto_id;
    public $cantidad;
    public $precio_unitario;
    public $total;
    public $fecha_venta;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (producto_id, cantidad, precio_unitario, total) VALUES (:producto_id, :cantidad, :precio_unitario, :total)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":precio_unitario", $this->precio_unitario);
        $stmt->bindParam(":total", $this->total);

        if ($stmt->execute()) {
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        }

        return ['success' => false, 'errors' => ['Error al crear la venta']];
    }

    public function read() {
        $query = "SELECT v.*, p.nombre as producto_nombre FROM " . $this->table_name . " v LEFT JOIN productos p ON v.producto_id = p.id ORDER BY v.fecha_venta DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne() {
        $query = "SELECT v.*, p.nombre as producto_nombre FROM " . $this->table_name . " v LEFT JOIN productos p ON v.producto_id = p.id WHERE v.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVentasByProducto($producto_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE producto_id = ? ORDER BY fecha_venta DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $producto_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
