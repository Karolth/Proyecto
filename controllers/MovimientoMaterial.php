<?php
// filepath: c:\xampp\htdocs\EasyCode\php\MovimientoMaterial.php
require_once '../config/conexion.php'; // Asegura que la conexión a la base de datos esté incluida

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si todos los campos están presentes
    if (!isset($_POST['idMaterial'], $_POST['estado'], $_POST['idMovimiento'])) {
        echo "Error: Faltan datos obligatorios.";
        exit;
    }

    // Obtener los datos enviados desde el cliente
    $idMaterial = trim($_POST['idMaterial']);
    $estado = trim($_POST['estado']);
    $idMovimiento = trim($_POST['idMovimiento']);

    // Validar que no estén vacíos
    if (empty($idMaterial) || empty($estado) || empty($idMovimiento)) {
        echo "Error: Todos los campos son obligatorios.";
        exit;
    }

    // Consulta para insertar el movimiento en la base de datos
    $sql = "INSERT INTO movimientomaterial (Estado, IdMovimiento, IdMaterial) 
            VALUES (:estado, :idMovimiento, :idMaterial)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idMovimiento', $idMovimiento);
        $stmt->bindParam(':idMaterial', $idMaterial);

        if ($stmt->execute()) {
            echo "Movimiento registrado exitosamente.";
        } else {
            echo "Error al registrar el movimiento.";
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
?>