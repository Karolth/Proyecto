
<?php
require_once "../config/conexion.php";

class ImagenModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function guardarImagen($nombreArchivo, $contenido) {
        $sql = "INSERT INTO imagenes_aprendiz (nombre_archivo, ruta_archivo) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombreArchivo, $contenido]);
    }
}
?>