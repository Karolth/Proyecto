<?php
include "conexion.php";

header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);
$action = isset($_GET['action']) ? $_GET['action'] : (isset($input['action']) ? $input['action'] : '');

if ($action == "Vehiculo") {
    if (!$input || !isset($input['Placa']) || !isset($input['IdTipoVehiculo'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    $Placa = htmlspecialchars($input['Placa']);
    $IdTipoVehiculo = htmlspecialchars($input['IdTipoVehiculo']);

    try {
        $stmt = $pdo->prepare("INSERT INTO Vehiculo (Placa, IdTipoVehiculo) VALUES (:Placa, :IdTipoVehiculo)");
        $stmt->bindParam(':Placa', $Placa);
        $stmt->bindParam(':IdTipoVehiculo', $IdTipoVehiculo);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Vehículo registrado correctamente']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} elseif ($action == "cargarTipo") {
    try {
        $sql_TipoVehiculo = $pdo->query("SELECT * FROM tipovehiculo");
        $TipoVehiculo = $sql_TipoVehiculo->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'TipoVehiculo' => $TipoVehiculo]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
?>
