<?php
require_once '../models/ModeloHistorial.php';

class HistorialController {
    public function getHistorial() {
        $historialModel = new HistorialModel();
        $result = $historialModel->fetchHistorial();
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function buscarSugerencias($term) {
        $historialModel = new HistorialModel();
        $result = $historialModel->fetchSugerencias($term);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}

// Manejar la solicitud
if (isset($_GET['action'])) {
    $controller = new HistorialController();
    if ($_GET['action'] === 'getHistorial') {
        $controller->getHistorial();
    } elseif ($_GET['action'] === 'buscarSugerencias' && isset($_GET['term'])) {
        $controller->buscarSugerencias($_GET['term']);
    }
}