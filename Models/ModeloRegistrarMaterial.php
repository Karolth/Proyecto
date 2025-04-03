<?php
require_once "../config/conexion.php";

class MaterialModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarMaterial($nombre, $referencia, $marca, $observaciones, $idTipoMaterial, $idUsuario, $idAprendiz) {
        $sql = "INSERT INTO material (Nombre, Referencia, Marca, Observaciones, IdTipoMaterial, IdUsuario, IdAprendiz) 
                VALUES (:nombre, :referencia, :marca, :observaciones, :idTipoMaterial, :idUsuario, :idAprendiz)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':referencia', $referencia);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':idTipoMaterial', $idTipoMaterial);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':idAprendiz', $idAprendiz);
        return $stmt->execute();
    }
}
?>