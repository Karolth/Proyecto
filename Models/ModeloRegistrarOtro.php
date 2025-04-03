<?php
require_once "../config/conexion.php";

class OtroMaterialModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarOtroMaterial($nombre, $observaciones, $idTipoMaterial, $idUsuario, $idAprendiz) {
        $sql = "INSERT INTO material (Nombre, Observaciones, IdTipoMaterial, IdUsuario, IdAprendiz) 
                VALUES (:nombre, :observaciones, :idTipoMaterial, :idUsuario, :idAprendiz)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':idTipoMaterial', $idTipoMaterial);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':idAprendiz', $idAprendiz);
        return $stmt->execute();
    }
}
?>