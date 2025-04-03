<?php
include '../config/conexion.php'; // Asegura que la conexión se incluya correctamente
require_once "../models/ModeloRegistrarVehiculo.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['placa'], $_POST['idTipoVehiculo'], 
               $_POST['idUsuario'], $_POST['idAprendiz'])
    ) {
        echo "Error: Faltan datos obligatorios.";
        exit;
    }

    // Obtener los datos del formulario
    $placa = trim(htmlspecialchars($_POST['placa']));
    $idTipoVehiculo = trim(htmlspecialchars($_POST['idTipoVehiculo']));
    $idUsuario = trim($_POST['idUsuario']);
    $idAprendiz = trim($_POST['idAprendiz']);

    // Validar que no estén vacíos
    if (empty($placa) || empty($idTipoVehiculo) || empty($idUsuario) || empty($idAprendiz)) {
        echo "Error: Todos los campos son obligatorios.";
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", ""); // Ajusta las credenciales
        $model = new VehiculoModel($pdo);

        if ($model->registrarVehiculo($placa, $idTipoVehiculo, $idUsuario, $idAprendiz)) {
            echo "Vehículo registrado correctamente.";
        } else {
            echo "Error al registrar el vehículo.";
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'cargarTipo') {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=easycode", "root", ""); // Ajusta las credenciales
        $model = new VehiculoModel($pdo);

        $tiposVehiculo = $model->obtenerTiposVehiculo();
        echo json_encode(['success' => true, 'TipoVehiculo' => $tiposVehiculo]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo "Método no permitido.";
}
?>