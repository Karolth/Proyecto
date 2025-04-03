<?php
require_once "../config/conexion.php";
require_once "../models/ModeloConsultarEstado.php";

$materiales = $_GET['materiales'] ?? "";
$vehiculos = $_GET['vehiculos'] ?? "";

$idsMateriales = explode(",", $materiales);
$idsVehiculos = explode(",", $vehiculos);

$estado = "Ingreso"; // Por defecto, si no hay registros previos

try {
    $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", ""); // Ajusta las credenciales
    $model = new EstadoModel($pdo);

    // Verificar el estado más reciente para los materiales
    $estadoMateriales = $model->obtenerEstadoMateriales($idsMateriales);
    if ($estadoMateriales) {
        $estado = $estadoMateriales['Estado'] === "Ingreso" ? "Salida" : "Ingreso";
    }

    // Verificar el estado más reciente para los vehículos
    $estadoVehiculos = $model->obtenerEstadoVehiculos($idsVehiculos);
    if ($estadoVehiculos) {
        $estado = $estadoVehiculos['Estado'] === "Ingreso" ? "Salida" : "Ingreso";
    }

    echo json_encode(["success" => true, "estado" => $estado]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al consultar estado: " . $e->getMessage()]);
}
?>