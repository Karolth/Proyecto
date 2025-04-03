<?php
require_once "../config/conexion.php";
require_once "../models/ModeloGuardar_ficha.php";

// Cabeceras para manejar JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Crear conexión
$conn = new mysqli("localhost", "root", "", "easycode");

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode([
        'success' => false, 
        'message' => 'Conexión fallida: ' . $conn->connect_error
    ]));
}

// Recibir datos JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validar datos recibidos
if (!$data) {
    die(json_encode([
        'success' => false, 
        'message' => 'No se recibieron datos válidos'
    ]));
}

try {
    // Comenzar transacción
    $conn->begin_transaction();
    $model = new FichaModel($conn);

    // Insertar programa
    $idPrograma = $model->insertarPrograma($data['nombrePrograma'], $data['tipoPrograma']);

    // Insertar ficha
    $model->insertarFicha(
        $data['numeroFicha'], 
        $data['fechaInicio'], 
        $data['fechaFin'], 
        $data['jornada'], 
        $idPrograma
    );

    // Confirmar transacción
    $conn->commit();

    // Respuesta exitosa
    echo json_encode([
        'success' => true, 
        'message' => 'Ficha creada exitosamente'
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    
    // Respuesta de error
    echo json_encode([
        'success' => false, 
        'message' => 'Error al crear ficha: ' . $e->getMessage()
    ]);
}

// Cerrar conexión
$conn->close();
?>