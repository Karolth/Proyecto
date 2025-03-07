<?php
include '../php/conexion.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $referencia = trim($_POST["referencia"]);
    $marca = trim($_POST["marca"]);
    $observaciones = trim($_POST["observaciones"]);

    if (empty($marca)) {
        echo json_encode(["exito" => false, "mensaje" => "La marca es obligatoria."]);
        exit;
    }

    try {
        // Obtener el IdTipoMaterial de "Computador"
        $sqlTipo = "SELECT IdTipoMaterial FROM tipomaterial WHERE IdTipoMaterial = '1' LIMIT 1";
        $stmtTipo = $pdo->prepare($sqlTipo);
        $stmtTipo->execute();
        $tipoMaterial = $stmtTipo->fetch();

        if ($tipoMaterial) {
            $idTipoMaterial = $tipoMaterial["IdTipoMaterial"];

            // Insertar el computador en la base de datos (sin el campo "Nombre")
            $sqlInsert = "INSERT INTO material (Referencia, Marca, Observaciones, IdTipoMaterial) 
                          VALUES (?, ?, ?, ?)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->execute([$referencia, $marca, $observaciones, $idTipoMaterial]);

            echo json_encode(["exito" => true, "mensaje" => "Computador registrado correctamente."]);
        } else {
            echo json_encode(["exito" => false, "mensaje" => "No se encontró el tipo de material 'Computador'."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["exito" => false, "mensaje" => "Error en la base de datos: " . $e->getMessage()]);
    }
}
?>
