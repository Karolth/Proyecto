<?php
// Incluir el archivo de conexión
include_once '../config/conexion.php';

// Iniciar sesión si no está iniciada
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE IdUsuario = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

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
