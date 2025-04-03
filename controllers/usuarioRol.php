<?php
include '../config/conexion.php';
require_once "../models/ModelousuarioRol.php";

header('Content-Type: application/json');

// Asegurar que los valores tienen el tipo correcto
$documento = isset($_POST['documento']) ? intval($_POST['documento']) : null;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$celular = isset($_POST['celular']) ? intval($_POST['celular']) : null;
$idRol = isset($_POST['rol']) ? intval($_POST['rol']) : null;

if (!$documento || !$nombre || !$email || !$celular || !$idRol) {
    echo json_encode(["error" => "Todos los campos son obligatorios."]);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", ""); // Ajusta las credenciales
    $pdo->beginTransaction();

    $model = new UsuarioRolModel($pdo);

    // Registrar usuario
    $idUsuario = $model->registrarUsuario($documento, $nombre, $email, $celular);
    if ($idUsuario) {
        // Asignar rol
        if ($model->asignarRol($idUsuario, $idRol)) {
            $pdo->commit();
            echo json_encode(["success" => "Usuario registrado y rol asignado correctamente."]);
        } else {
            $pdo->rollBack();
            echo json_encode(["error" => "Error al asignar el rol."]);
        }
    } else {
        $pdo->rollBack();
        echo json_encode(["error" => "Error al registrar el usuario."]);
    }
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["error" => "Excepción capturada: " . $e->getMessage()]);
}
?>