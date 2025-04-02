<?php
// Include your connection file
include 'conexion.php'; // Assuming your connection code is in this file

try {
    // Prepare SQL query to get movement data
    $sql = "SELECT mm.IdMovimientoMaterial, mm.Estado, mm.IdMovimiento, mm.IdMaterial, 
                   m.Referencia, m.Marca, m.IdTipoMaterial
            FROM movimientomaterial mm
            JOIN material m ON mm.IdMaterial = m.IdMaterial
            ORDER BY mm.IdMovimientoMaterial";
    
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