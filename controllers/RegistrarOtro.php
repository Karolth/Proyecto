<?php
require_once '../config/conexion.php'; // Asegura que la conexión se incluya correctamente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si todos los campos están presentes
    if (
        !isset($_POST['nombre'], $_POST['observaciones'], 
               $_POST['idTipoMaterial'], $_POST['idUsuario'], $_POST['idAprendiz'])
    ) {
        echo "Error: Faltan datos obligatorios.";
        exit;
    }

    // Obtener los datos del formulario
    $nombre = trim($_POST['nombre']);
    $observaciones = trim($_POST['observaciones']);
    $idTipoMaterial = trim($_POST['idTipoMaterial']);
    $idUsuario = trim($_POST['idUsuario']);
    $idAprendiz = trim($_POST['idAprendiz']);

    // Validar que no estén vacíos los campos obligatorios
    if (empty($nombre) || empty($idTipoMaterial)) {
        echo "Error: El nombre y el tipo de material son obligatorios.";
        exit;
    }

    // Consulta para insertar el material
    $sql = "INSERT INTO material (Nombre, Observaciones, IdTipoMaterial, IdUsuario, IdAprendiz) 
                VALUES (:nombre, :observaciones, :idTipoMaterial, :idUsuario, :idAprendiz)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':idTipoMaterial', $idTipoMaterial);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':idAprendiz', $idAprendiz);

        if ($stmt->execute()) {
            echo "Material registrado exitosamente.";
        } else {
            echo "Error al registrar el material.";
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
?>