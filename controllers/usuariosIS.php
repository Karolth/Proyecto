<?php
// Incluir la conexión
include_once '../config/conexion.php';
require_once "../models/ModelousuariosIS.php";
session_start();

header('Content-Type: application/json');

// Obtener y decodificar la entrada JSON
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input['action'])) {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    exit;
}

$action = htmlspecialchars($input['action']);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", ""); // Ajusta las credenciales
    $model = new UsuarioModel($pdo);

    if ($action === "login") {
        if (empty($input['Documento']) || empty($input['password'])) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            exit;
        }

        $documento = htmlspecialchars($input['Documento']);
        $password = htmlspecialchars($input['password']);

        // Verificar credenciales de administrador
    //   {  if ($documento === "0000000000" && $password === "0000000000") {
    //         $_SESSION['user_id'] = 0; // ID especial para el super admin
    //         $_SESSION['Documento'] = $documento;
    //         echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
    //         exit;
    //     }

        // Verificar si el Documento existe
        $user = $model->obtenerUsuarioPorDocumento($documento);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'El documento no está registrado']);
            exit;
        }

        // Verificar si la contraseña es correcta
        if ($user['Documento'] !== $password) {
            echo json_encode(['success' => false, 'message' => 'La contraseña es incorrecta']);
            exit;
        }

        // Obtener el rol del usuario
        $role = $model->obtenerRolPorUsuario($user['IdUsuario']);
        if (!$role) {
            echo json_encode(['success' => false, 'message' => 'Error al obtener el rol del usuario.']);
            exit;
        }

        if (strtolower($role['Rol']) !== 'administrador') {
            echo json_encode(['success' => false, 'message' => 'Acceso denegado. Solo los administradores pueden ingresar.']);
            exit;
        }

        // Si es administrador, iniciar sesión
        $_SESSION['user_id'] = $user['IdUsuario'];
        $_SESSION['Documento'] = $user['Documento'];
        echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
    } elseif ($action === "getPerfil") {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            exit;
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>