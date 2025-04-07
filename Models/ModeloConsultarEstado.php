<?php
class EstadoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerEstadoPorUsuario($idUsuario) {
        $query = "SELECT Movimiento FROM movimiento WHERE IdUsuario = ? ORDER BY FechaHora DESC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$idUsuario]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadoPorAprendiz($idAprendiz) {
        $query = "SELECT Movimiento FROM movimiento WHERE IdAprendiz = ? ORDER BY FechaHora DESC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$idAprendiz]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadoMateriales($idsMateriales) {
        // Convertir los IDs en una lista separada por comas para la consulta
        $placeholders = implode(',', array_fill(0, count($idsMateriales), '?'));
        $query = "SELECT Estado FROM movimientomaterial WHERE IdMaterial IN ($placeholders) ORDER BY FechaHora DESC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($idsMateriales);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>