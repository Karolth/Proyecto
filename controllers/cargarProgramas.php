<?php
require_once "../config/conexion.php"; // Aquí ya se define $pdo
require_once "../models/ModeloGuardar_ficha.php";

header('Content-Type: application/json');

// Solo permitir método GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido. Se requiere GET.'
    ]);
    exit;
}

// Crear instancia del modelo con la conexión $pdo
$programaModel = new ProgramaModel($pdo);

try {
    $programas = $programaModel->obtenerProgramas();

    if ($programas && count($programas) > 0) {
        echo json_encode([
            'success' => true,
            'data' => $programas
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontraron programas de formación.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los programas: ' . $e->getMessage()
    ]);
}
