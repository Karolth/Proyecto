<?php
require_once "../config/conexion.php";

class PerfilModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerPerfilPorId($id) {
        $stmt = $this->pdo->prepare("SELECT Nombre, Documento, Email, Celular FROM usuario WHERE IdUsuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPerfil($id, $email, $celular) {
        $stmt = $this->pdo->prepare("UPDATE usuario SET Email = ?, Celular = ? WHERE IdUsuario = ?");
        return $stmt->execute([$email, $celular, $id]);
    }
}

?>