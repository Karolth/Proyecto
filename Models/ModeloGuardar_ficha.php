<?php
require_once "../config/conexion.php";

class FichaModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertarPrograma($nombrePrograma, $tipoPrograma) {
        $stmt = $this->conn->prepare("INSERT INTO programa (Nombre, Version, Fecha, IdTipoPrograma) 
                                      VALUES (?, '1', CURRENT_DATE, 
                                      (SELECT IdTipoPrograma FROM tipoprograma WHERE TipoPrograma = ?))");
        $stmt->bind_param("ss", $nombrePrograma, $tipoPrograma);
        $stmt->execute();
        $idPrograma = $this->conn->insert_id;
        $stmt->close();
        return $idPrograma;
    }

    public function insertarFicha($numeroFicha, $fechaInicio, $fechaFin, $jornada, $idPrograma) {
        $stmt = $this->conn->prepare("INSERT INTO ficha (Numficha, FechaInicio, FechaFinal, Jornada, IdPrograma) 
                                      VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $numeroFicha, $fechaInicio, $fechaFin, $jornada, $idPrograma);
        $stmt->execute();
        $stmt->close();
    }
}
?>