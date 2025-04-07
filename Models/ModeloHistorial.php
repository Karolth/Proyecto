<?php
include_once '../config/conexion.php'; 
header('Content-Type: application/json');

// Obtener el parámetro 'action' desde la URL
$action = $_GET['action'] ?? '';

if ($action === "obtenerHistorial") {
    try {
        $stmt = $pdo->query("SELECT 
            a.Nombre AS NombreAprendiz,
            a.Documento,
            m.Nombre AS NombreMaterial,
            m.Referencia,
            v.Placa,
            tv.Tipo AS TipoVehiculo,
            mo.FechaHora,
            mo.movimiento
        FROM movimiento mo
        JOIN aprendiz a ON mo.IdAprendiz = a.IdAprendiz
        JOIN material m ON m.IdMaterial = m.IdMaterial
        JOIN vehiculo v ON v.IdVehiculo = v.IdVehiculo
        JOIN tipovehiculo tv ON v.IdTipoVehiculo = tv.IdTipoVehiculo");

        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener historial: ' . $e->getMessage()]);
    }
    exit();
}
?>
