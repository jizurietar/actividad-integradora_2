<?php

require_once '../config/database.php';
require_once '../models/Venta.php';
require_once '../models/Producto.php';
require_once '../services/VentaService.php';

class VentaController {
    private $db;
    private $venta;
    private $ventaService;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->venta = new Venta($this->db);
        $this->ventaService = new VentaService($this->db);
    }

    public function index() {
        $ventas = $this->venta->read();
        return $ventas;
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->producto_id) && isset($data->cantidad)) {
            $result = $this->ventaService->procesarVenta($data->producto_id, $data->cantidad);
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Faltan datos requeridos: producto_id y cantidad']]);
        }
    }

    public function show($id) {
        $venta = $this->venta->readOne();
        if ($venta) {
            echo json_encode($venta);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Venta no encontrada']]);
        }
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);
        $path_parts = explode('/', trim($path, '/'));

        header('Content-Type: application/json');

        try {
            switch ($method) {
                case 'GET':
                    if (isset($path_parts[2]) && is_numeric($path_parts[2])) {
                        $this->venta->id = $path_parts[2];
                        $this->show($path_parts[2]);
                    } else {
                        $ventas = $this->index();
                        echo json_encode($ventas);
                    }
                    break;

                case 'POST':
                    $this->create();
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

if (basename($_SERVER['PHP_SELF']) == 'VentaController.php') {
    $controller = new VentaController();
    $controller->handleRequest();
}
?>
