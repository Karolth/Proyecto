<?php
include '../config/conexion.php';
require_once "../models/ModeloMostrarElemento.php";

$idUsuario = $_GET['idUsuario'] ?? null;
$tipoUsuario = $_GET['tipoUsuario'] ?? null;
$tipoConsulta = $_GET['tipoConsulta'] ?? 'materiales'; // Por defecto, se asume que se consultan materiales

if (!$idUsuario) {
    echo json_encode(['error' => 'ID de usuario no proporcionado']);
    exit;
}

try {
    $model = new ElementoModel($pdo);

    if ($tipoConsulta === 'materiales') {
        $materiales = $model->obtenerMaterialesPorUsuario($idUsuario, $tipoUsuario);
        $resultado = [];
        foreach ($materiales as $row) {
            $resultado[] = [
                'IdMaterial' => $row['IdMaterial'],
                'Nombre' => $row['Nombre'],
                'Referencia' => $row['Referencia'],
                'Marca' => $row['Marca'],
                'Tipo' => $row['tipomaterial'],
                'Estado' => $row['EstadoMovimiento'],
            ];
        }
        echo json_encode($resultado);
    } elseif ($tipoConsulta === 'vehiculo') {
        $vehiculos = $model->obtenerVehiculosPorUsuario($idUsuario, $tipoUsuario);
        $resultado = [];
        foreach ($vehiculos as $row) {
            $resultado[] = [
                'IdVehiculo' => $row['IdVehiculo'],
                'Placa' => $row['Placa'],
                'Tipo' => $row['tipovehiculo'],
                'Estado' => $row['EstadoMovimientoVehiculo'] ?? null,
            ];
        }
        echo json_encode(['vehiculo' => $resultado]);
    } else {
        echo json_encode(['error' => 'Tipo de consulta no válido']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>