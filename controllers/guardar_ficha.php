<?php
// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "easycode";

// Cabeceras para manejar JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode([
        'success' => false, 
        'message' => 'Conexión fallida: ' . $conn->connect_error
    ]));
}

// Recibir datos JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validar datos recibidos
if (!$data) {
    die(json_encode([
        'success' => false, 
        'message' => 'No se recibieron datos válidos'
    ]));
}

try {
    // Comenzar transacción
    $conn->begin_transaction();

    // Insertar programa
    $stmt = $conn->prepare("INSERT INTO programa (Nombre, Version, Fecha, IdTipoPrograma) 
                             VALUES (?, '1', CURRENT_DATE, 
                             (SELECT IdTipoPrograma FROM tipoprograma WHERE TipoPrograma = ?))");
    $stmt->bind_param("ss", $data['nombrePrograma'], $data['tipoPrograma']);
    $stmt->execute();
    $idPrograma = $conn->insert_id;
    $stmt->close();

    // Insertar ficha
    $stmt = $conn->prepare("INSERT INTO ficha (Numficha, FechaInicio, FechaFinal, Jornada, IdPrograma) 
                             VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", 
        $data['numeroFicha'], 
        $data['fechaInicio'], 
        $data['fechaFin'], 
        $data['jornada'], 
        $idPrograma
    );
    $stmt->execute();
    $stmt->close();

    // Confirmar transacción
    $conn->commit();

    // Respuesta exitosa
    echo json_encode([
        'success' => true, 
        'message' => 'Ficha creada exitosamente'
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    
    // Respuesta de error
    echo json_encode([
        'success' => false, 
        'message' => 'Error al crear ficha: ' . $e->getMessage()
    ]);
}

// Cerrar conexión
$conn->close();
?>