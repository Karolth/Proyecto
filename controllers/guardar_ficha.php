<?php
require_once "../config/conexion.php";
require_once "../models/ModeloGuardar_ficha.php";

header('Content-Type: application/json');

// Verificar la conexión PDO
if (!isset($pdo)) {
    echo json_encode(['success' => false, 'message' => 'Error: No se pudo establecer la conexión a la base de datos (PDO).']);
    exit;
}

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido. Se requiere POST.']);
    exit;
}

// Validar la presencia de los datos del formulario y el archivo
if (!isset($_POST['programaFormacion'], $_POST['jornada'], $_POST['tipoPrograma'], $_POST['fechaInicio'], $_POST['fechaFin'], $_POST['numeroFicha'], $_FILES['archivoExcel'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos del formulario o el archivo Excel.'
    ]);
    exit;
}

// Asignar los datos del formulario
$idPrograma = intval($_POST['programaFormacion']);
$jornada = trim($_POST['jornada']);
$tipoPrograma = trim($_POST['tipoPrograma']);
$fechaInicio = trim($_POST['fechaInicio']);
$fechaFin = trim($_POST['fechaFin']);
$numeroFicha = trim($_POST['numeroFicha']);
$archivoExcel = $_FILES['archivoExcel'];

// Validar que no haya campos vacíos
if (empty($idPrograma) || empty($jornada) || empty($tipoPrograma) || empty($fechaInicio) || empty($fechaFin) || empty($numeroFicha) || $archivoExcel['error'] === UPLOAD_ERR_NO_FILE) {
    echo json_encode([
        'success' => false,
        'message' => 'Todos los campos del formulario y el archivo Excel son obligatorios.'
    ]);
    exit;
}

// Validar el archivo Excel
$allowedMimeTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain'];
if ($archivoExcel['error'] !== UPLOAD_ERR_OK || !in_array($archivoExcel['type'], $allowedMimeTypes)) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al cargar el archivo Excel o tipo de archivo no válido. Se permiten archivos CSV y Excel.'
    ]);
    exit;
}

$archivoTemp = $archivoExcel['tmp_name'];

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    $model = new FichaModel($pdo);

    // Insertar ficha
    $fichaInsertada = $model->insertarFicha($numeroFicha, $fechaInicio, $fechaFin, $jornada, $idPrograma);
    if (!$fichaInsertada) {
        throw new Exception("Error al insertar la ficha.");
    }
    $idFicha = $pdo->lastInsertId(); // Obtener el ID de la ficha insertada

    // Importar aprendices desde el archivo
    $model->importarAprendicesDesdeExcel($archivoTemp);

    // Asignar aprendices a la ficha
    $lineas = file($archivoTemp);
    $i = 0;
    foreach ($lineas as $linea) {
        if ($i != 0) { // Saltar encabezado
            $datos = explode(";", $linea);
            $documento = intval(trim($datos[1] ?? 0));

            if (!empty($documento)) {
                $idAprendiz = $model->obtenerIdAprendizPorDocumento($documento);
                if ($idAprendiz) {
                    $model->asignarAprendizAFicha($idFicha, $idAprendiz);
                } else {
                    // Opcional: Manejar el caso en que no se encuentra el aprendiz
                    // Puedes registrar un error o decidir no asignarlo.
                    error_log("Advertencia: No se encontró el aprendiz con documento " . $documento . " para asignar a la ficha " . $idFicha);
                }
            }
        }
        $i++;
    }

    // Confirmar transacción
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Ficha creada y aprendices registrados exitosamente.'
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear ficha: ' . $e->getMessage()
    ]);
} finally {
    // Cerrar la conexión PDO
    $pdo = null;
}
?>