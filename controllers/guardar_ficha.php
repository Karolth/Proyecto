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
if (!isset($_POST['nombreFicha'], $_POST['jornada'], $_POST['tipoPrograma'], $_POST['fechaInicio'], $_POST['fechaFin'], $_POST['numeroFicha'], $_FILES['archivoExcel'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos del formulario o el archivo Excel'
    ]);
    exit;
}

$nombrePrograma = $_POST['nombreFicha'];
$jornada = $_POST['jornada'];
$tipoPrograma = $_POST['tipoPrograma'];
$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];
$numeroFicha = $_POST['numeroFicha'];
$archivoExcel = $_FILES['archivoExcel'];

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