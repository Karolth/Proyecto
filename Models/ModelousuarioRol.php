<?php
require_once "../config/conexion.php";

class UsuarioRolModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarUsuario($documento, $nombre, $email, $celular) {
        $sql = "INSERT INTO usuario (Documento, Nombre, Email, Celular) VALUES (:documento, :nombre, :email, :celular)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':documento', $documento, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':celular', $celular, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function asignarRol($idUsuario, $idRol) {
        $sql = "INSERT INTO usuarioRol (IdUsuario, IdRol) VALUES (:idUsuario, :idRol)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':idRol', $idRol, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>