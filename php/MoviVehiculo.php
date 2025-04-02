<?php
// Include your connection file
include 'conexion.php'; // Assuming your connection code is in this file

try {
    // Prepare SQL query to get movement data
    $sql =  "SELECT mv.IdMovimientoVehiculo, mv.Estado, mv.IdMovimiento, mv.IdVehiculo , v.Placa, v.IdTipoVehiculo
            FROM movimientovehiculo mv
            JOIN vehiculo v ON v.IdVehiculo = mv.IdVehiculo
            ORDER BY mv.IdMovimientoVehiculo";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $movimientos = $stmt->fetchAll();
    
    // Return data as JSON
    header('Content-Type: application/json');
    echo json_encode($movimientos);
    
} catch (PDOException $e) {
    // Return error as JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al obtener datos: ' . $e->getMessage()]);
}
?>

    