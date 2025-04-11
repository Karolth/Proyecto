<?php
require_once "../config/conexion.php";
require_once "../models/ModelousuariosIS.php";

header('Content-Type: application/json');

try {
    $model = new UsuarioModel($pdo);
    $historial = $model->obtenerHistorialLogin();

    if ($historial) {
        echo json_encode(['success' => true, 'data' => $historial]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron registros de inicio de sesión.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener el historial: ' . $e->getMessage()]);
}