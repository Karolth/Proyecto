<?php
require_once "../config/conexion.php";

class EstadoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerEstadoMateriales($idsMateriales) {
        if (empty($idsMateriales)) {
            return null;
        }

        $placeholders = implode(",", array_fill(0, count($idsMateriales), "?"));
        $stmt = $this->pdo->prepare("SELECT Estado FROM movimientomaterial WHERE IdMaterial IN ($placeholders) ORDER BY IdMovimientoMaterial DESC LIMIT 1");
        $stmt->execute($idsMateriales);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadoVehiculos($idsVehiculos) {
        if (empty($idsVehiculos)) {
            return null;
        }

        $placeholders = implode(",", array_fill(0, count($idsVehiculos), "?"));
        $stmt = $this->pdo->prepare("SELECT Estado FROM movimientovehiculo WHERE IdVehiculo IN ($placeholders) ORDER BY IdMovimientoVehiculo DESC LIMIT 1");
        $stmt->execute($idsVehiculos);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>