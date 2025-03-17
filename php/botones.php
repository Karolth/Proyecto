<?php
include 'conexion.php'; // Asegúrate de que la ruta sea correcta

if (!isset($pdo)) {
    die("Error: No se pudo conectar a la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movimiento = $_POST["movimiento"]; // No hace falta escape con PDO

    try {
        $stmt = $pdo->prepare("INSERT INTO movimiento (Movimiento, FechaHora) VALUES (:movimiento, NOW())");
        $stmt->bindParam(':movimiento', $movimiento, PDO::PARAM_STR);
        $stmt->execute();

        echo "Registro de $movimiento guardado correctamente.";
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}
?>