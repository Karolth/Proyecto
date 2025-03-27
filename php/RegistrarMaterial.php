<?php
require_once '../php/conexion.php'; // Asegura que la conexión se incluya correctamente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si todos los campos están presentes
    if (
        !isset($_POST['nombre'], $_POST['referencia'], $_POST['marca'], $_POST['observaciones'], 
               $_POST['idTipoMaterial'], $_POST['idUsuario'], $_POST['idAprendiz'])
    ) {
        echo "Error: Faltan datos obligatorios.";
        exit;
    }

    // Obtener los datos del formulario
    $nombre = trim($_POST['nombre']);
    $referencia = trim($_POST['referencia']);
    $marca = trim($_POST['marca']);
    $observaciones = trim($_POST['observaciones']);
    $idTipoMaterial = trim($_POST['idTipoMaterial']);
    $idUsuario = trim($_POST['idUsuario']);
    $idAprendiz = trim($_POST['idAprendiz']);

    // Validar que no estén vacíos
    if (empty($nombre) || empty($referencia) || empty($marca) || empty($idTipoMaterial) || empty($idUsuario) || empty($idAprendiz)) {
        echo "Error: Todos los campos son obligatorios.";
        exit;
    }

    // Consulta para insertar el material
    $sql = "INSERT INTO material (Nombre, Referencia, Marca, Observaciones, IdTipoMaterial, IdUsuario, IdAprendiz) 
            VALUES (:nombre, :referencia, :marca, :observaciones, :idTipoMaterial, :idUsuario, :idAprendiz)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':referencia', $referencia);
        $stmt->bindParam(':marca', $marca);
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