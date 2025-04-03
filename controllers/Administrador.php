<?php
require_once "../config/conexion.php"; // Conexión a la base de datos
require_once "../models/ModeloAdministrador.php";

if (isset($_GET['documento'])) {
    $documento = $_GET['documento'];

    try {
         // Ajusta las credenciales
        $model = new AdministradorModel($pdo);

        $tipoPersona = "";
        $idUsuario = null;
        $idAprendiz = null;

        // Buscar aprendiz
        $aprendiz = $model->buscarAprendiz($documento);

        if ($aprendiz) {
            $tipoPersona = 'aprendiz';
            $idAprendiz = $aprendiz['IdAprendiz'];

            // Imagen
            $rutaImagen = "../public/Imagenes/$documento.jpg";
            if (!file_exists($rutaImagen)) {
                $rutaImagen = "../public/Imagenes/default-user.png";
            }
        } else {
            // Buscar usuario
            $usuario = $model->buscarUsuario($documento);

            if ($usuario) {
                $tipoPersona = 'usuario';
                $idUsuario = $usuario['IdUsuario'];
                $rutaImagen = "../public/Imagenes/default-user.png";
            } else {
                echo json_encode(['error' => 'No se encontró el documento en la base de datos.']);
                exit;
            }
        }

        // Determinar el último movimiento
        $ultimoMovimiento = $model->obtenerUltimoMovimiento($idUsuario, $idAprendiz);
        $nuevoMovimiento = ($ultimoMovimiento && $ultimoMovimiento['Movimiento'] === 'Entrada') ? 'Salida' : 'Entrada';

        // Insertar nuevo movimiento
        $model->insertarMovimiento($nuevoMovimiento, $idUsuario, $idAprendiz);

        // Respuesta al frontend
        echo json_encode([
            'tipo' => $tipoPersona,
            'datos' => $aprendiz ? $aprendiz : $usuario,
            'imagen' => $rutaImagen,
            'movimiento' => $nuevoMovimiento
        ]);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
}
?>