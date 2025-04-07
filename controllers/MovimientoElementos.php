<?php
require '../config/conexion.php';
require_once "../models/ModeloMovimientosElementos.php";

$data = json_decode(file_get_contents("php://input"), true);

$materiales = $data['materiales'] ?? [];
$vehiculos = $data['vehiculos'] ?? [];


try {
    $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", ""); // Ajusta las credenciales
    $pdo->beginTransaction();

    $model = new MovimientoModel($pdo);

    // Obtener el último ID de movimiento
    $ultimoMovimiento = $model->obtenerUltimoMovimiento();

    $idMovimiento = $ultimoMovimiento['IdMovimiento'];
    $movimiento = $ultimoMovimiento['Movimiento'];
    foreach ($materiales as $idMaterial) {
        $model->insertarMaterial($movimiento, $idMovimiento, $idMaterial);
    }
    foreach ($vehiculos as $idVehiculo) {
        $model->insertarVehiculo($movimiento, $idMovimiento, $idVehiculo);
    }
    $pdo->commit();
    echo json_encode(["success" => true, "message" => "Movimientos registrados correctamente."]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["success" => false, "message" => "Error al registrar los movimientos: " . $e->getMessage()]);
}
?>