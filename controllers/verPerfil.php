<?php
include_once '../config/conexion.php';
require_once "../models/ModeloverPerfil.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$userId = $_SESSION['user_id'];

// Lee el JSON recibido
$input = json_decode(file_get_contents("php://input"), true);

$action = isset($input['action']) ? $input['action'] : '';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", "");
    $model = new PerfilModel($pdo);

    if ($action === 'getPerfil') {
        $user = $model->obtenerPerfilPorId($userId);
        if ($user) {
            echo json_encode([
                'success' => true,
                'Nombre' => $user['Nombre'],
                'Documento' => $user['Documento'],
                'Email' => $user['Email'],
                'Celular' => $user['Celular']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }

    } elseif ($action === 'modificar') {
        $email = $input['email'] ?? '';
        $celular = $input['celular'] ?? '';

        if (!$email || !$celular) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit;
        }

        $resultado = $model->actualizarPerfil($userId, $email, $celular);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el perfil']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
