<?php
require '../config/conexion.php';
require_once "../models/ModeloMovimientosElementos.php";

$data = json_decode(file_get_contents("php://input"), true);

$materiales = $data['materiales'] ?? [];
$vehiculos = $data['vehiculos'] ?? [];
$estado = $data['estado'] ?? null;

if (!$estado) {
    echo json_encode(["success" => false, "message" => "Error: Estado no proporcionado."]);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", ""); // Ajusta las credenciales
    $pdo->beginTransaction();

    $model = new MovimientoModel($pdo);

    // Obtener el último ID de movimiento
    $ultimoMovimiento = $model->obtenerUltimoMovimiento();

    if (!$ultimoMovimiento) {
        // Crear un nuevo movimiento si no existe
        $idMovimiento = $model->crearMovimiento($estado);
    } else {
        // Usar el ID del último movimiento
        $idMovimiento = $ultimoMovimiento['IdMovimiento'];
    }

    // Insertar materiales
    foreach ($materiales as $idMaterial) {
        $model->insertarMaterial($estado, $idMovimiento, $idMaterial);
    }

    // Insertar vehículos
    foreach ($vehiculos as $idVehiculo) {
        $model->insertarVehiculo($estado, $idMovimiento, $idVehiculo);
    }

    $pdo->commit();
    echo json_encode(["success" => true, "message" => "Movimientos registrados correctamente."]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["success" => false, "message" => "Error al registrar los movimientos: " . $e->getMessage()]);
}
?>