<?php

class Producto {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $nombre;
    public $descripcion;
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

        if (empty(trim($this->descripcion))) {
            $errors[] = "La descripción del producto no puede estar vacía";
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

        $query = "INSERT INTO " . $this->table_name . " (nombre,descripcion, stock, precio) VALUES (:nombre,:descripcion, :stock, :precio)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":precio", $this->precio);

        if ($stmt->execute()) {
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        }

        return ['success' => false, 'errors' => ['Error al crear el producto']];
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE deleted = 0  ORDER BY nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
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

        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, descripcion = :descripcion, stock = :stock, precio = :precio WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return ['success' => true];
        }

        return ['success' => false, 'errors' => ['Error al actualizar el producto']];
    }

    public function delete() {
        //Se mejora el proceso de borrado dejando solo eliminado logico
        $query = "UPDATE  " . $this->table_name . " SET deleted = 1  WHERE id = ?";

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
