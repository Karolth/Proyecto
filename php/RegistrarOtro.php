<?php
include 'conexion.php'; // Incluimos la conexión con PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Referencia = trim($_POST["NombreOtro"]);
    $observaciones = trim($_POST["ObservacionesOtro"]);

    if (empty($Referencia)) {
        echo json_encode(["exito" => false, "mensaje" => "El nombre es obligatorio."]);
        exit;
    }

    try {
        
        
            $idTipoMaterial = 2;

            // Insertar el nuevo material
            $sqlInsert = "INSERT INTO material (Referencia, Observaciones, IdTipoMaterial) VALUES (?, ?, ?)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->execute([$Referencia, $observaciones, $idTipoMaterial]);

            echo json_encode(["exito" => true, "mensaje" => "Material registrado correctamente."]);
      
    } catch (PDOException $e) {
        echo json_encode(["exito" => false, "mensaje" => "Error en la base de datos: " . $e->getMessage()]);
    }
}
?>
