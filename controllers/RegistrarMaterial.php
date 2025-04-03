<?php
require_once '../config/conexion.php'; // Asegura que la conexión se incluya correctamente
require_once "../models/ModeloRegistrarMaterial.php";

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

    try {
        $model = new MaterialModel($pdo);

        if ($model->registrarMaterial($nombre, $referencia, $marca, $observaciones, $idTipoMaterial, $idUsuario, $idAprendiz)) {
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