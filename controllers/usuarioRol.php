<?php
include '../config/conexion.php';

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
    $pdo->beginTransaction();

    // Insertar usuario
    $stmt_usuario = $pdo->prepare("INSERT INTO usuario (Documento, Nombre, Email, Celular) VALUES (:documento, :nombre, :email, :celular)");
    $stmt_usuario->bindParam(':documento', $documento, PDO::PARAM_INT);
    $stmt_usuario->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt_usuario->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt_usuario->bindParam(':celular', $celular, PDO::PARAM_INT);

    if ($stmt_usuario->execute()) {
        $idUsuario = $pdo->lastInsertId();

        // Insertar en la tabla usuarioRol
        $stmt_usuarioRol = $pdo->prepare("INSERT INTO usuarioRol (IdUsuario, IdRol) VALUES (:idUsuario, :idRol)");
        $stmt_usuarioRol->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt_usuarioRol->bindParam(':idRol', $idRol, PDO::PARAM_INT);

        if ($stmt_usuarioRol->execute()) {
            $pdo->commit();
            echo json_encode(["success" => "Usuario registrado y rol asignado correctamente."]);
        } else {
            $pdo->rollBack();
            echo json_encode(["error" => "Error al asignar el rol: " . implode(" ", $stmt_usuarioRol->errorInfo())]);
        }
    } else {
        $pdo->rollBack();
        echo json_encode(["error" => "Error al registrar el usuario: " . implode(" ", $stmt_usuario->errorInfo())]);
    }
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["error" => "Excepción capturada: " . $e->getMessage()]);
}
?>
