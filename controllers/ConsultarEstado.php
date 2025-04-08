<?php
require_once "../config/conexion.php";
require_once "../models/ModeloConsultarEstado.php";

$idUsuario = $_GET['idUsuario'] ?? null; // ID del usuario a verificar
$idAprendiz = $_GET['idAprendiz'] ?? null; // ID del aprendiz a verificar

if (!$idUsuario && !$idAprendiz) {
    echo json_encode(["success" => false, "message" => "Debe proporcionar un IdUsuario o un IdAprendiz."]);
    exit;
}

try {
    $model = new EstadoModel($pdo);

    $estado = null;

    // Verificar el estado más reciente para el usuario
    if ($idUsuario) {
        $estadoUsuario = $model->obtenerEstadoPorUsuario($idUsuario);
        if ($estadoUsuario) {
            $estado = $estadoUsuario['Movimiento']; // Obtener el estado directamente de la tabla movimiento
        }
    }

    // Verificar el estado más reciente para el aprendiz
    if ($idAprendiz) {
        $estadoAprendiz = $model->obtenerEstadoPorAprendiz($idAprendiz);
        if ($estadoAprendiz) {
            $estado = $estadoAprendiz['Movimiento']; // Obtener el estado directamente de la tabla movimiento
        }
    }

    if ($estado === null) {
        echo json_encode(["success" => false, "message" => "No se encontró estado para la persona seleccionada."]);
    } else {
        echo json_encode(["success" => true, "estado" => $estado]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al consultar estado: " . $e->getMessage()]);
}
?>