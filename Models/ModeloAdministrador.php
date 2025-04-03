<?php
require_once "../config/conexion.php"; // Conexión a la base de datos

class AdministradorModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function buscarAprendiz($documento) {
        $stmt = $this->pdo->prepare("SELECT a.IdAprendiz, a.Nombre, a.RH, a.Documento, tp.TipoPrograma, p.Nombre AS Programa
                                     FROM aprendiz a
                                     JOIN fichaaprendiz fa ON a.IdAprendiz = fa.IdAprendiz
                                     JOIN ficha f ON fa.IdFicha = f.IdFicha
                                     JOIN programa p ON f.IdPrograma = p.IdPrograma
                                     JOIN tipoprograma tp ON p.IdTipoPrograma = tp.IdTipoPrograma
                                     WHERE a.Documento = :documento");
        $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarUsuario($documento) {
        $stmt = $this->pdo->prepare("SELECT u.IdUsuario, u.Nombre, u.Email, r.Rol
                                     FROM usuario u
                                     JOIN usuariorol ur ON u.IdUsuario = ur.IdUsuario
                                     JOIN rol r ON ur.IdRol = r.IdRol
                                     WHERE u.Documento = :documento");
        $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimoMovimiento($idUsuario, $idAprendiz) {
        $stmt = $this->pdo->prepare("SELECT Movimiento FROM movimiento WHERE IdUsuario = :idUsuario OR IdAprendiz = :idAprendiz ORDER BY FechaHora DESC LIMIT 1");
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':idAprendiz', $idAprendiz, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertarMovimiento($movimiento, $idUsuario, $idAprendiz) {
        $stmt = $this->pdo->prepare("INSERT INTO movimiento (FechaHora, Movimiento, IdUsuario, IdAprendiz) VALUES (NOW(), :movimiento, :idUsuario, :idAprendiz)");
        $stmt->bindParam(':movimiento', $movimiento, PDO::PARAM_STR);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':idAprendiz', $idAprendiz, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>