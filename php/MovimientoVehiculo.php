<?php
require_once '../php/conexion.php'; // Asegura la conexión a la base de datos

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
            // Si no existe, insertar un nuevo registro
            $sqlInsert = "INSERT INTO movimientovehiculo (Estado, IdMovimiento, idVehiculo) VALUES (:estado, :idMovimiento, :idVehiculo)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':estado', $estado);
            $stmtInsert->bindParam(':idMovimiento', $idMovimiento);
            $stmtInsert->bindParam(':idVehiculo', $idVehiculo);

            if ($stmtInsert->execute()) {
                echo "Movimiento registrado exitosamente.";
            } else {
                echo "Error al registrar el movimiento.";
            }
        }catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
} else {
    echo "Error: Método de solicitud no permitido.";
}
?>
