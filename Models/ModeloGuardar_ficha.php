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

    public function importarAprendicesDesdeExcel($archivoTmp) {
        $lineas = file($archivoTmp);
        $i = 0;
        foreach ($lineas as $linea) {
            if ($i != 0) { // saltar encabezado
                $datos = explode(";", $linea);
                $nombre    = trim($datos[0] ?? '');
                $documento = intval($datos[1] ?? 0);
                $rh        = trim($datos[2] ?? '');

                if (!empty($documento)) {
                    // Verificar si el aprendiz ya existe
                    $check = $this->conn->prepare("SELECT Documento FROM aprendiz WHERE Documento = ?");
                    $check->bind_param("i", $documento);
                    $check->execute();
                    $check->store_result();

                    if ($check->num_rows == 0) {
                        $insert = $this->conn->prepare("INSERT INTO aprendiz (Nombre, Documento, RH) VALUES (?, ?, ?)");
                        $insert->bind_param("sis", $nombre, $documento, $rh);
                        $insert->execute();
                        $insert->close();
                    } else {
                        $update = $this->conn->prepare("UPDATE aprendiz SET Nombre = ?, RH = ? WHERE Documento = ?");
                        $update->bind_param("ssi", $nombre, $rh, $documento);
                        $update->execute();
                        $update->close();
                    }
                    $check->close();
                }
            }
            $i++;
        }
    }
}
?>
