<?php
require '../config/conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

$materiales = $data['materiales'] ?? [];
$vehiculos = $data['vehiculos'] ?? [];
$estado = $data['estado'] ?? null;

if (!$estado) {
    echo json_encode(["success" => false, "message" => "Error: Estado no proporcionado."]);
    exit;
}

try {
    $pdo->beginTransaction();

    // Obtener el último ID de movimiento para evitar duplicados
    $stmtUltimoMovimiento = $pdo->prepare("SELECT IdMovimiento FROM movimiento ORDER BY IdMovimiento DESC LIMIT 1");
    $stmtUltimoMovimiento->execute();
    $ultimoMovimiento = $stmtUltimoMovimiento->fetch(PDO::FETCH_ASSOC);

    if (!$ultimoMovimiento) {
        // Crear un nuevo movimiento si no existe
        $stmtCrearMovimiento = $pdo->prepare("INSERT INTO movimiento (Movimiento, Fecha) VALUES (:movimiento, NOW())");
        $stmtCrearMovimiento->execute([':movimiento' => $estado]);
        $idMovimiento = $pdo->lastInsertId();
    } else {
        // Usar el ID del último movimiento
        $idMovimiento = $ultimoMovimiento['IdMovimiento'];
    }

    // Insertar materiales si no existe un registro igual
    if (!empty($materiales)) {
        $stmtMateriales = $pdo->prepare("INSERT INTO movimientomaterial (Estado, IdMovimiento, IdMaterial) 
                                         SELECT :estado, :idMovimiento, :idMaterial 
                                         WHERE NOT EXISTS (
                                            SELECT 1 FROM movimientomaterial 
                                            WHERE Estado = :estado AND IdMovimiento = :idMovimiento AND IdMaterial = :idMaterial
                                         )");

        foreach ($materiales as $idMaterial) {
            $stmtMateriales->execute([
                ':estado' => $estado,
                ':idMovimiento' => $idMovimiento,
                ':idMaterial' => $idMaterial
            ]);
        }
    }

    // Insertar vehículos si no existe un registro igual
    if (!empty($vehiculos)) {
        $stmtVehiculos = $pdo->prepare("INSERT INTO movimientovehiculo (Estado, IdMovimiento, IdVehiculo) 
                                        SELECT :estado, :idMovimiento, :idVehiculo 
                                        WHERE NOT EXISTS (
                                            SELECT 1 FROM movimientovehiculo 
                                            WHERE Estado = :estado AND IdMovimiento = :idMovimiento AND IdVehiculo = :idVehiculo
                                        )");

        foreach ($vehiculos as $idVehiculo) {
            $stmtVehiculos->execute([
                ':estado' => $estado,
                ':idMovimiento' => $idMovimiento,
                ':idVehiculo' => $idVehiculo
            ]);
        }
    }

    $pdo->commit();
    echo json_encode(["success" => true, "message" => "Movimientos registrados correctamente."]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["success" => false, "message" => "Error al registrar los movimientos: " . $e->getMessage()]);
}
?>
