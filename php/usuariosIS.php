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
    if (!isset($input['Documento']) || !isset($input['password'])) { // Asegurar que coincida con el JSON
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    $Documento = htmlspecialchars($input['Documento']);
    $pass = htmlspecialchars($input['password']); // Se asume que es igual al Documento

    try {
        // Consulta SQL corregida
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE Documento = :Documento AND Documento = :password");
        $stmt->bindParam(':Documento', $Documento);
        $stmt->bindParam(':password', $pass);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario existe y si el "password" (Documento) coincide
        if ($user && $user['Documento'] === $pass) {
            $_SESSION['user_id'] = $user['IdUsuario'];
            $_SESSION['Documento'] = $user['Documento'];
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Documento o contraseña incorrectos']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al iniciar sesión: ' . $e->getMessage()]);
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
