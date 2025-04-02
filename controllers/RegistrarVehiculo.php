<?php
include '../config/conexion.php'; // Asegura que la conexión se incluya correctamente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['placa'], $_POST['idTipoVehiculo'],
                $_POST['idUsuario'], $_POST['idAprendiz'])
    ) {
        echo "Error: Faltan datos obligatorios.";
        exit;
    }

    // Obtener los datos del formulario
    $Placa = trim(htmlspecialchars($_POST['placa']));
    $IdTipoVehiculo = trim(htmlspecialchars($_POST['idTipoVehiculo']));
    $idUsuario = trim($_POST['idUsuario']);
    $idAprendiz = trim($_POST['idAprendiz']);

    // Validar que no estén vacíos
    if (empty($Placa) || empty($IdTipoVehiculo) || empty($idUsuario) || empty($idAprendiz)) {
        echo "Error: Todos los campos son obligatorios.";
        exit;
    }

    // Consulta para insertar el vehículo

    $sql = "INSERT INTO vehiculo (Placa, IdTipoVehiculo, IdUsuario, IdAprendiz) VALUES (:placa, :idTipoVehiculo, :idUsuario, :idAprendiz)";    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':placa', $Placa);
        $stmt->bindParam(':idTipoVehiculo', $IdTipoVehiculo);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':idAprendiz', $idAprendiz);

        if ($stmt->execute()) {
            echo "Vehículo registrado correctamente.";
        } else {
            echo "Error al registrar el vehículo.";
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'cargarTipo') {
    try {
        $sql_TipoVehiculo = $pdo->query("SELECT * FROM tipovehiculo");
        $TipoVehiculo = $sql_TipoVehiculo->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'TipoVehiculo' => $TipoVehiculo]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo "Método no permitido.";
}
