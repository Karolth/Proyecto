<?php
require 'conexion.php';

$materiales = $_GET['materiales'] ?? "";
$vehiculos = $_GET['vehiculos'] ?? "";

$idsMateriales = explode(",", $materiales);
$idsVehiculos = explode(",", $vehiculos);

$estado = "Ingreso"; // Por defecto, si no hay registros previos

try {
    // Verificar el estado más reciente para los materiales
    if (!empty($idsMateriales)) {
        $placeholders = implode(",", array_fill(0, count($idsMateriales), "?"));
        $stmt = $pdo->prepare("SELECT Estado FROM movimientomaterial WHERE IdMaterial IN ($placeholders) ORDER BY IdMovimientoMaterial DESC LIMIT 1");
        $stmt->execute($idsMateriales);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $estado = $result['Estado'] === "Ingreso" ? "Salida" : "Ingreso";
        }
    }

    // Verificar el estado más reciente para los vehículos
    if (!empty($idsVehiculos)) {
        $placeholders = implode(",", array_fill(0, count($idsVehiculos), "?"));
        $stmt = $pdo->prepare("SELECT Estado FROM movimientovehiculo WHERE IdVehiculo IN ($placeholders) ORDER BY IdMovimientoVehiculo DESC LIMIT 1");
        $stmt->execute($idsVehiculos);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $estado = $result['Estado'] === "Ingreso" ? "Salida" : "Ingreso";
        }
    }

    echo json_encode(["success" => true, "estado" => $estado]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al consultar estado: " . $e->getMessage()]);
}
?>
