<?php
require_once "../config/conexion.php";

class MovimientoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerUltimoMovimiento() {
        $stmt = $this->pdo->prepare("SELECT IdMovimiento,Movimiento FROM movimiento ORDER BY IdMovimiento DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearMovimiento($estado) {
        $stmt = $this->pdo->prepare("INSERT INTO movimiento (Movimiento, Fecha) VALUES (:movimiento, NOW())");
        $stmt->execute([':movimiento' => $estado]);
        return $this->pdo->lastInsertId();
    }

    public function insertarMaterial($movimiento, $idMovimiento, $idMaterial) {
        $stmt = $this->pdo->prepare("INSERT INTO movimientomaterial (Estado, IdMovimiento, IdMaterial) 
                                     SELECT :estado, :idMovimiento, :idMaterial 
                                     WHERE NOT EXISTS (
                                        SELECT 1 FROM movimientomaterial 
                                        WHERE Estado = :estado AND IdMovimiento = :idMovimiento AND IdMaterial = :idMaterial
                                     )");
        $stmt->execute([
            ':estado' => $movimiento,
            ':idMovimiento' => $idMovimiento,
            ':idMaterial' => $idMaterial
        ]);
        
    }

    public function insertarVehiculo($estado, $idMovimiento, $idVehiculo) {
        $stmt = $this->pdo->prepare("INSERT INTO movimientovehiculo (Estado, IdMovimiento, IdVehiculo) 
                                     SELECT :estado, :idMovimiento, :idVehiculo 
                                     WHERE NOT EXISTS (
                                        SELECT 1 FROM movimientovehiculo 
                                        WHERE Estado = :estado AND IdMovimiento = :idMovimiento AND IdVehiculo = :idVehiculo
                                     )");
        $stmt->execute([
            ':estado' => $estado,
            ':idMovimiento' => $idMovimiento,
            ':idVehiculo' => $idVehiculo
        ]);
    }
}
?>