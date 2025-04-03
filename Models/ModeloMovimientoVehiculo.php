<?php
require_once "../config/conexion.php";

class MovimientoVehiculoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarMovimientoVehiculo($estado, $idMovimiento, $idVehiculo) {
        $sql = "INSERT INTO movimientovehiculo (Estado, IdMovimiento, idVehiculo) VALUES (:estado, :idMovimiento, :idVehiculo)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idMovimiento', $idMovimiento);
        $stmt->bindParam(':idVehiculo', $idVehiculo);
        return $stmt->execute();
    }
}
?>