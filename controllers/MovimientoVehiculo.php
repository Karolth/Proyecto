<?php
require_once "../models/ModeloMovimientoVehiculo.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['idVehiculo'], $_POST['estado'], $_POST['idMovimiento'])) {
        echo "Error: Faltan datos obligatorios.";
        exit;
    }

    $idVehiculo = trim($_POST['idVehiculo']);
    $estado = trim($_POST['estado']);
    $idMovimiento = trim($_POST['idMovimiento']);

    if (empty($idVehiculo) || empty($estado) || empty($idMovimiento)) {
        echo "Error: Todos los campos son obligatorios.";
        exit;
    }

    try {
        // $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", ""); // Ajusta las credenciales
        $model = new MovimientoVehiculoModel($pdo);

        if ($model->registrarMovimientoVehiculo($estado, $idMovimiento, $idVehiculo)) {
            echo "Movimiento registrado exitosamente.";
        } else {
            echo "Error al registrar el movimiento.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Método de solicitud no permitido.";
}
?>