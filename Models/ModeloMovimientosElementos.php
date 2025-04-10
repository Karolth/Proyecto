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
        // Verificar si ya existe un registro con ese material en estado "Entrada"
        $stmt = $this->pdo->prepare("SELECT 1 FROM movimientomaterial WHERE Estado = :estado AND IdMaterial = :idMaterial");
        $stmt->execute([
            ':estado' => $movimiento,
            ':idMaterial' => $idMaterial
        ]);
    
        if (!$stmt->fetch()) {
            // Solo insertar si no existe en ese estado
            $insert = $this->pdo->prepare("INSERT INTO movimientomaterial (Estado, IdMovimiento, IdMaterial)
                                           VALUES (:estado, :idMovimiento, :idMaterial)");
            $insert->execute([
                ':estado' => $movimiento,
                ':idMovimiento' => $idMovimiento,
                ':idMaterial' => $idMaterial
            ]);
        }
    }
    

    public function insertarVehiculo($estado, $idMovimiento, $idVehiculo) {
        $stmt = $this->pdo->prepare("SELECT 1 FROM movimientovehiculo WHERE Estado = :estado AND IdVehiculo = :idVehiculo");
        $stmt->execute([
            ':estado' => $estado,
            ':idVehiculo' => $idVehiculo
        ]);
    
        if (!$stmt->fetch()) {
            $insert = $this->pdo->prepare("INSERT INTO movimientovehiculo (Estado, IdMovimiento, IdVehiculo)
                                           VALUES (:estado, :idMovimiento, :idVehiculo)");
            $insert->execute([
                ':estado' => $estado,
                ':idMovimiento' => $idMovimiento,
                ':idVehiculo' => $idVehiculo
            ]);
        }
    }    
}
?>