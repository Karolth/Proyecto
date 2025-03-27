<?php
// Incluir la conexión
include_once 'conexion.php';
session_start();

// Obtener y decodificar la entrada JSON
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input['action'])) {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    exit;
}

$action = htmlspecialchars($input['action']);

if ($action === "login") {
    if (empty($input['Documento']) || empty($input['password'])) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $Documento = htmlspecialchars($input['Documento']);
    $pass = htmlspecialchars($input['password']);

    try {
        // Verificar si el Documento existe en la tabla 'usuario'
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE Documento = :Documento");
        $stmt->bindParam(':Documento', $Documento);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'El documento no está registrado']);
            exit;
        }

        // Verificar si la contraseña es correcta
        if ($user['Documento'] !== $pass) { 
            echo json_encode(['success' => false, 'message' => 'La contraseña es incorrecta']);
            exit;
        }

        // **MOVER LA CONSULTA DEL ROL AQUÍ ANTES DE INICIAR SESIÓN**
        $stmt = $pdo->prepare("
            SELECT r.Rol FROM usuarioRol ur
            INNER JOIN rol r ON ur.IdRol = r.IdRol
            WHERE ur.IdUsuario = :IdUsuario");
        $stmt->bindParam(':IdUsuario', $user['IdUsuario']);
        $stmt->execute();
        $role = $stmt->fetch(PDO::FETCH_ASSOC);

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

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al iniciar sesión']);
    }
} elseif ($action === "getPerfil") {
    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        exit;
    }

    $userId = $_SESSION['user_id'];

    try {
        // Obtener los datos del usuario desde la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE IdUsuario = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode([
                'success' => true,
                'Documento' => $user['Documento']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener los datos del perfil: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
?>
