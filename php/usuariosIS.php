<?php
//Incluir el php de conexion//
include_once 'conexion.php';

session_start();

//Obtener y decodificar la entrada JSON//
$input =json_decode(file_get_contents("php://input"), true);
if(!$input || !isset($input['action'])){
    echo json_encode(['success'=> false, 'message' => 'Action']);
}

$action = $input['action'];


if($action== "login") {
        //Obtener y modificar la entrada JSON//
        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input || !isset($input['Documento']) || !isset($input['password'])) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            exit;
        }

        $Documento = htmlspecialchars($input['Documento']);
        $pass = htmlspecialchars($input['password']);

        try {
            //Preparar la consulta de seleccion para comprobar el usuario//
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE Documento = :Documento AND Documento = :password");
            $stmt->bindParam(':Documento', $Documento);
            $stmt->bindParam(':password', $pass);  // Se agrega el parámetro para comparar directamente
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['user_id'] = $user['IdUsuario'];
                $_SESSION['Documento'] = $user['Documento'];
                echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Documento o contraseña incorrectos']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al iniciar sesión: ' . $e->getMessage()]);
            } 
        } 
    elseif ($action == "getPerfil") {
            // Verificar si el Documento esta autenticado 
            if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            exit;
            } 
            $userId = $_SESSION['user_id'];
            
             try {
             // Obtener los datos del ususario desde la base de datos 
             $stmt = $pdo->prepare("SELECT * FROM usuario WHERE IdUsuario = :id");
             $stmt->bindParam(':id', $userId);
             $stmt->execute();
             $user = $stmt->fetch (PDO::FETCH_ASSOC);
             if ($user) {
                 echo json_encode([
                     'success' =>true,
                     'Documento' => $user ['Documento'],
                 ]);
             } else {
                 echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
             }
             } catch (PDOException $e) {
             echo json_encode(['success' => false, 'message' => 'Error al obtedner los datos del perfil:'. $e->getMessage()]);
             }
     } else {
    echo json_encode(['success' => false, 'message' => 'Accion no valida']);
    }
?>