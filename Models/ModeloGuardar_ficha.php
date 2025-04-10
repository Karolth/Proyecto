<?php
require_once "../config/conexion.php";


class ProgramaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerProgramas() {
        $stmt = $this->pdo->query("SELECT IdPrograma, Nombre FROM programa");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class FichaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insertarFicha($numeroFicha, $fechaInicio, $fechaFin, $jornada, $idPrograma) {
        $stmt = $this->pdo->prepare("INSERT INTO ficha (Numficha, FechaInicio, FechaFinal, Jornada, IdPrograma)
                                     VALUES (?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $numeroFicha);
        $stmt->bindParam(2, $fechaInicio);
        $stmt->bindParam(3, $fechaFin);
        $stmt->bindParam(4, $jornada);
        $stmt->bindParam(5, $idPrograma, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function importarAprendicesDesdeExcel($archivoTmp) {
        $lineas = file($archivoTmp);
        $i = 0;
        foreach ($lineas as $linea) {
            if ($i != 0) { // saltar encabezado
                $datos = explode(";", $linea);
                $nombre     = trim($datos[0] ?? '');
                $documento = intval($datos[1] ?? 0);
                $rh         = trim($datos[2] ?? '');

                if (!empty($documento)) {
                    // Verificar si el aprendiz ya existe
                    $check = $this->pdo->prepare("SELECT Documento FROM aprendiz WHERE Documento = ?");
                    $check->bindParam(1, $documento, PDO::PARAM_INT);
                    $check->execute();
                    $aprendizExistente = $check->fetch(PDO::FETCH_ASSOC);

                    if (!$aprendizExistente) {
                        $insert = $this->pdo->prepare("INSERT INTO aprendiz (Nombre, Documento, RH) VALUES (?, ?, ?)");
                        $insert->bindParam(1, $nombre);
                        $insert->bindParam(2, $documento, PDO::PARAM_INT);
                        $insert->bindParam(3, $rh);
                        $insert->execute();
                        $insert->closeCursor();
                    } else {
                        $update = $this->pdo->prepare("UPDATE aprendiz SET Nombre = ?, RH = ? WHERE Documento = ?");
                        $update->bindParam(1, $nombre);
                        $update->bindParam(2, $rh);
                        $update->bindParam(3, $documento, PDO::PARAM_INT);
                        $update->execute();
                        $update->closeCursor();
                    }
                    $check->closeCursor();
                }
            }
            $i++;
        }
    }

    public function asignarAprendizAFicha($idFicha, $idAprendiz) {
        $stmt = $this->pdo->prepare("INSERT INTO fichaaprendiz (IdFicha, IdAprendiz) VALUES (?, ?)");
        $stmt->bindParam(1, $idFicha, PDO::PARAM_INT);
        $stmt->bindParam(2, $idAprendiz, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerIdAprendizPorDocumento($documento) {
        $stmt = $this->pdo->prepare("SELECT IdAprendiz FROM aprendiz WHERE Documento = ?");
        $stmt->bindParam(1, $documento, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultado ? $resultado['IdAprendiz'] : null;
    }
}

?>
