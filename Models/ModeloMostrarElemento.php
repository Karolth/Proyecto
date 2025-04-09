<?php
require_once "../config/conexion.php";

class ElementoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerMaterialesPorUsuario($idUsuario, $tipoUsuario) {
        $consulta = "";
        if ($tipoUsuario === 'aprendiz') {
            $consulta ="SELECT m.*, 
                            tm.Tipo AS tipomaterial, 
                            mm.Estado AS EstadoMovimiento
                         FROM material m
                         JOIN tipomaterial tm ON m.idTipoMaterial = tm.idTipoMaterial
                         LEFT JOIN movimientomaterial mm ON m.IdMaterial = mm.IdMaterial
                         WHERE m.idAprendiz = :idUsuario
                         ORDER BY m.IdMaterial  DESC";
        } elseif ($tipoUsuario === 'usuario') {
            $consulta = "SELECT m.*, 
                            tm.Tipo AS tipomaterial, 
                            mm.Estado AS EstadoMovimiento
                         FROM material m
                         JOIN tipomaterial tm ON m.idTipoMaterial = tm.idTipoMaterial
                         LEFT JOIN movimientomaterial mm ON m.IdMaterial = mm.IdMaterial
                         WHERE m.idUsuario = :idUsuario
                         ORDER BY m.IdMaterial  DESC";
        }
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerVehiculosPorUsuario($idUsuario, $tipoUsuario) {
        $consulta = "";
        if ($tipoUsuario === 'aprendiz') {
            $consulta = "SELECT v.*, 
                            tv.Tipo AS tipovehiculo, 
                            mv.Estado AS EstadoMovimientoVehiculo
                        FROM vehiculo v
                        JOIN tipovehiculo tv ON v.idTipoVehiculo = tv.idTipoVehiculo
                        LEFT JOIN movimientovehiculo mv ON v.IdVehiculo = mv.IdVehiculo
                        WHERE v.idAprendiz = :idUsuario
                        ORDER BY v.IdVehiculo DESC";
        } elseif ($tipoUsuario === 'usuario') {
            $consulta = "SELECT v.*, 
                            tv.Tipo AS tipovehiculo, 
                            mv.Estado AS EstadoMovimientoVehiculo
                        FROM vehiculo v
                        JOIN tipovehiculo tv ON v.idTipoVehiculo = tv.idTipoVehiculo
                        LEFT JOIN movimientovehiculo mv ON v.IdVehiculo = mv.IdVehiculo
                        WHERE v.idUsuario = :idUsuario
                        ORDER BY v.IdVehiculo DESC";
        }
        $stmt = $this->pdo->prepare($consulta);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>