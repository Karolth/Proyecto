<?php
require_once "../config/conexion.php";

class UsuarioModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerUsuarioPorDocumento($documento) {
        $sql = "SELECT * FROM usuario WHERE Documento = :documento";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerRolPorUsuario($idUsuario) {
        $sql = "SELECT r.Rol FROM usuarioRol ur
                INNER JOIN rol r ON ur.IdRol = r.IdRol
                WHERE ur.IdUsuario = :idUsuario";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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