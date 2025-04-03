<?php
require_once "../config/conexion.php";

class PerfilModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerPerfilPorId($idUsuario) {
        $sql = "SELECT * FROM usuario WHERE IdUsuario = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>