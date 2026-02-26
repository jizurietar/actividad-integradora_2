<?php

class Producto {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $nombre;
    public $stock;
    public $precio;
    public $fecha_creacion;
    public $fecha_actualizacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function validate() {
        $errors = [];

        if (empty(trim($this->nombre))) {
            $errors[] = "El nombre del producto no puede estar vacío";
        }

        if (!is_numeric($this->stock) || $this->stock < 0) {
            $errors[] = "El stock debe ser un número mayor o igual a 0";
        }

        if (!is_numeric($this->precio) || $this->precio <= 0) {
            $errors[] = "El precio debe ser un número mayor a 0";
        }

        return $errors;
    }

    public function create() {
        $errors = $this->validate();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $query = "INSERT INTO " . $this->table_name . " (nombre, stock, precio) VALUES (:nombre, :stock, :precio)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":precio", $this->precio);

        if ($stmt->execute()) {
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        }

        return ['success' => false, 'errors' => ['Error al crear el producto']];
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nombre = $row['nombre'];
            $this->stock = $row['stock'];
            $this->precio = $row['precio'];
            $this->fecha_creacion = $row['fecha_creacion'];
            $this->fecha_actualizacion = $row['fecha_actualizacion'];
            return true;
        }

        return false;
    }

    public function update() {
        $errors = $this->validate();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, stock = :stock, precio = :precio WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return ['success' => true];
        }

        return ['success' => false, 'errors' => ['Error al actualizar el producto']];
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return ['success' => true];
        }

        return ['success' => false, 'errors' => ['Error al eliminar el producto']];
    }

    public function updateStock($cantidad) {
        if ($this->stock + $cantidad < 0) {
            return ['success' => false, 'errors' => ['No se puede tener stock negativo']];
        }

        $query = "UPDATE " . $this->table_name . " SET stock = stock + ? WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cantidad);
        $stmt->bindParam(2, $this->id);

        if ($stmt->execute()) {
            $this->stock += $cantidad;
            return ['success' => true];
        }

        return ['success' => false, 'errors' => ['Error al actualizar el stock']];
    }
}
?>
