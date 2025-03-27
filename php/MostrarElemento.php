<?php
include 'conexion.php';

$idUsuario = $_GET['idUsuario'] ?? null;
$tipoUsuario = $_GET['tipoUsuario'] ?? null;
$tipoConsulta = $_GET['tipoConsulta'] ?? 'materiales'; // Por defecto, se asume que se consultan materiales

if (!$idUsuario) {
    echo json_encode(['error' => 'ID de usuario no proporcionado']);
    exit;
}

try {
    if ($tipoConsulta === 'materiales') {
        $consulta = "";
        if ($tipoUsuario === 'aprendiz') {
            $consulta = "SELECT m.*, tm.Tipo AS tipomaterial 
            FROM material m
            JOIN tipomaterial tm ON m.idTipoMaterial = tm.idTipoMaterial
            WHERE m.idAprendiz = :i
            ORDER BY m.IdMaterial DESC";
        } elseif ($tipoUsuario === 'usuario') {
            $consulta = "SELECT m.*, tm.Tipo AS tipomaterial 
            FROM material m
            JOIN tipomaterial tm ON m.idTipoMaterial = tm.idTipoMaterial
            WHERE m.idUsuario = :i
            ORDER BY m.IdMaterial DESC";
        }
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam("i", $idUsuario);
        $stmt->execute();
        $materiales = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $materiales[] = [
                'IdMaterial' => $row['IdMaterial'],
                'Referencia' => $row['Referencia'],
                'Marca' => $row['Marca'],
                'Tipo' => $row['tipomaterial']
            ];
        }
        echo json_encode($materiales);
    } elseif ($tipoConsulta === 'vehiculo') {
        $consulta = "";
        if ($tipoUsuario === 'aprendiz') {
            $consulta = "SELECT v.*, tv.Tipo AS tipoVehiculo 
            FROM vehiculo v
            JOIN tipovehiculo tv ON v.idTipoVehiculo = tv.idTipoVehiculo
            WHERE v.idAprendiz = :i
            ORDER BY v.IdVehiculo DESC";
        } elseif ($tipoUsuario === 'usuario') {
            $consulta = "SELECT v.*, tv.Tipo AS tipoVehiculo 
            FROM vehiculo v
            JOIN tipovehiculo tv ON v.idTipoVehiculo = tv.idTipoVehiculo
            WHERE v.idUsuario = :i
            ORDER BY v.IdVehiculo DESC";
        }
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam("i", $idUsuario);
        $stmt->execute();
        $vehiculo = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vehiculo[] = [
                'IdVehiculo' => $row['IdVehiculo'],
                'Placa' => $row['Placa'],
                'Tipo' => $row['tipoVehiculo']
            ];
        }
        echo json_encode(['vehiculo' => $vehiculo]);
    } else {
        echo json_encode(['error' => 'Tipo de consulta no válido']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>