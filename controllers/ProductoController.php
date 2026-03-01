<?php

require_once '../config/database.php';
require_once '../models/Producto.php';

class ProductoController {
    private $db;
    private $producto;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->producto = new Producto($this->db);
    }

    public function index() {
        $productos = $this->producto->read();
        return $productos;
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->nombre) && isset($data->stock) && isset($data->precio)) {
            $this->producto->nombre = $data->nombre;
            $this->producto->stock = $data->stock;
            $this->producto->precio = $data->precio;

            $result = $this->producto->create();
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Faltan datos requeridos']]);
        }
    }

    public function show($id) {
        $this->producto->id = $id;
        if ($this->producto->readOne()) {
            echo json_encode([
                'id' => $this->producto->id,
                'nombre' => $this->producto->nombre,
                'stock' => $this->producto->stock,
                'precio' => $this->producto->precio,
                'fecha_creacion' => $this->producto->fecha_creacion,
                'fecha_actualizacion' => $this->producto->fecha_actualizacion
            ]);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Producto no encontrado']]);
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->nombre) && isset($data->stock) && isset($data->precio)) {
            $this->producto->id = $id;
            $this->producto->nombre = $data->nombre;
            $this->producto->stock = $data->stock;
            $this->producto->precio = $data->precio;

            $result = $this->producto->update();
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Faltan datos requeridos']]);
        }
    }

    public function delete($id) {
        $this->producto->id = $id;
        $result = $this->producto->delete();
        echo json_encode($result);
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        header('Content-Type: application/json');
        $id = htmlspecialchars($_GET["id"]);

        try {
            switch ($method) {
                case 'GET':
                    
                    if ($id) {
                        $this->show($id);
                    } else {
                        $productos = $this->index();
                        echo json_encode($productos);
                    }
                    
                    break;

                case 'POST':
                    $this->create();
                    break;

                case 'PUT':
                    if ($id) {
                        $this->update($id);
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['ID no proporcionado']]);
                    }
                    break;

                case 'DELETE':
                    if ($id) {
                        $this->delete($id);
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['ID no proporcionado']]);
                    }
                    break;

                default:
                    echo json_encode(['success' => false, 'errors' => ['Método no permitido']]);
                    break;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'errors' => [$e->getMessage()]]);
        }
    }
}

if (basename($_SERVER['PHP_SELF']) == 'ProductoController.php') {
    $controller = new ProductoController();
    $controller->handleRequest();
}
?>
