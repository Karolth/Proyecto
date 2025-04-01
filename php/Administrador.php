<?php
require_once "conexion.php"; // Conexión a la base de datos

if (isset($_GET['documento'])) {
    $documento = $_GET['documento'];

    try {
        $tipoPersona = "";
        $idUsuario = null;
        $idAprendiz = null;

        // Buscar en la tabla aprendiz
        $stmt = $pdo->prepare("SELECT a.IdAprendiz, a.Nombre, a.RH, a.Documento, tp.TipoPrograma, p.Nombre AS Programa
                               FROM aprendiz a
                               JOIN fichaaprendiz fa ON a.IdAprendiz = fa.IdAprendiz
                               JOIN ficha f ON fa.IdFicha = f.IdFicha
                               JOIN programa p ON f.IdPrograma = p.IdPrograma
                               JOIN tipoprograma tp ON p.IdTipoPrograma = tp.IdTipoPrograma
                               WHERE a.Documento = :documento");
        $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
        $stmt->execute();
        $aprendiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($aprendiz) {
            $tipoPersona = 'aprendiz';
            $idAprendiz = $aprendiz['IdAprendiz'];

            // Imagen
            $rutaImagen = "../Imagenes/$documento.jpg";
            if (!file_exists($rutaImagen)) {
                $rutaImagen = "../Imagenes/default-user.png";
            }
        } else {
            // Buscar en la tabla usuario
            $stmt = $pdo->prepare("SELECT u.IdUsuario, u.Nombre, u.Email, r.Rol
                                   FROM usuario u
                                   JOIN usuariorol ur ON u.IdUsuario = ur.IdUsuario
                                   JOIN rol r ON ur.IdRol = r.IdRol
                                   WHERE u.Documento = :documento");
            $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $tipoPersona = 'usuario';
                $idUsuario = $usuario['IdUsuario'];
                $rutaImagen = "../Imagenes/default-user.png";
            } else {
                echo json_encode(['error' => 'No se encontró el documento en la base de datos.']);
                exit;
            }
        }

        // Determinar el último movimiento
        $stmt = $pdo->prepare("SELECT Movimiento FROM movimiento WHERE IdUsuario = :idUsuario OR IdAprendiz = :idAprendiz ORDER BY FechaHora DESC LIMIT 1");
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':idAprendiz', $idAprendiz, PDO::PARAM_INT);
        $stmt->execute();
        $ultimoMovimiento = $stmt->fetch(PDO::FETCH_ASSOC);

        $nuevoMovimiento = ($ultimoMovimiento && $ultimoMovimiento['Movimiento'] === 'Entrada') ? 'Salida' : 'Entrada';

        // Insertar nuevo movimiento
        $stmt = $pdo->prepare("INSERT INTO movimiento (FechaHora, Movimiento, IdUsuario, IdAprendiz) VALUES (NOW(), :movimiento, :idUsuario, :idAprendiz)");
        $stmt->bindParam(':movimiento', $nuevoMovimiento, PDO::PARAM_STR);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':idAprendiz', $idAprendiz, PDO::PARAM_INT);
        $stmt->execute();

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
