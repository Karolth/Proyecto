<?php
require_once "../config/conexion.php";

class VehiculoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarVehiculo($placa, $idTipoVehiculo, $idUsuario, $idAprendiz) {
        $sql = "INSERT INTO vehiculo (Placa, IdTipoVehiculo, IdUsuario, IdAprendiz) 
                VALUES (:placa, :idTipoVehiculo, :idUsuario, :idAprendiz)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':placa', $placa);
        $stmt->bindParam(':idTipoVehiculo', $idTipoVehiculo);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':idAprendiz', $idAprendiz);
        return $stmt->execute();
    }

    public function obtenerTiposVehiculo() {
        $sql = "SELECT * FROM tipovehiculo";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>