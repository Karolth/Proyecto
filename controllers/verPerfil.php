<?php
include_once '../config/conexion.php';
require_once "../models/PerfilModel.php";

// Iniciar sesión si no está iniciada
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", ""); // Ajusta las credenciales
    $model = new PerfilModel($pdo);

    // Obtener los datos del perfil
    $user = $model->obtenerPerfilPorId($userId);
    if ($user) {
        echo json_encode([
            'success' => true,
            'Nombre' => $user['Nombre'],
            'Documento' => $user['Documento'],
            'Email' => $user['Email'],
            'Telefono' => $user['Telefono']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener los datos del perfil: ' . $e->getMessage()]);
}
?>