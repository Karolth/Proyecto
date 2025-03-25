<?php
include 'conexion.php';


$idUsuario = $_GET['idUsuario'] ?? null;
$tipoUsuario = $_GET['tipoUsuario'] ?? null;

if (!$idUsuario) {
    echo json_encode(['error' => 'ID de usuario no proporcionado']);
    exit;
}

try {
    $consulta = "";
        if ($tipoUsuario === 'aprendiz') {
            $consulta = "SELECT m.*, tm.Tipo AS tipomaterial 
            FROM material m
            JOIN tipomaterial tm ON m.idTipoMaterial = tm.idTipoMaterial
            WHERE m.idAprendiz = ?
            ORDER BY m.idMaterial DESC";
        } else {
            $consulta = "SELECT m.*, tm.Tipo AS tipomaterial 
            FROM material m
            JOIN tipomaterial tm ON m.idTipoMaterial = tm.idTipoMaterial
            WHERE m.idUsuario = ?
            ORDER BY m.idMaterial DESC";
        }
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $materiales = [];
    while ($fila = $resultado->fetch_assoc()) {
        $materiales[] = $fila;
    }

    echo json_encode(['materiales' => $materiales]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>