<?php
include 'conexion.php'; // Asegúrate de que la ruta sea correcta

if (!isset($pdo)) {
    die("Error: No se pudo conectar a la base de datos.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movimiento = $_POST['movimiento'];
    $id = $_POST['id'];
    $tipoUsuario = $_POST['tipoUsuario'];

    // Validar los datos recibidos
    if (empty($movimiento) || empty($id) || empty($tipoUsuario)) {
        echo "Datos incompletos.";
        exit;
    }

    // Procesar el movimiento según el tipo de usuario
    if ($tipoUsuario === 'aprendiz') {
        // Lógica para registrar el movimiento del aprendiz
        $stmt = $pdo->prepare("INSERT INTO movimiento (IdAprendiz, Movimiento) VALUES (:id, :movimiento)");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':movimiento', $movimiento, PDO::PARAM_STR);
        $stmt->execute();
        echo "Movimiento registrado para el aprendiz.";
    } elseif ($tipoUsuario === 'usuario') {
        // Lógica para registrar el movimiento del usuario
        $stmt = $pdo->prepare("INSERT INTO movimientos (IdUsuario, Movimiento) VALUES (:id, :movimiento)");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':movimiento', $movimiento, PDO::PARAM_STR);
        $stmt->execute();
        echo "Movimiento registrado para el usuario.";
    } else {
        echo "Tipo de usuario no válido.";
    }
    exit;
}

if (isset($_GET['documento'])) {
    $documento = $_GET['documento'];

    try {
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
            // Construir la ruta de la imagen
            $rutaImagen = "../Imagenes/$documento.jpg";

            // Verificar si la imagen existe
            if (!file_exists($rutaImagen)) {
                $rutaImagen = "../Imagenes/default-user.png"; // Imagen por defecto
            }

            // Enviar los datos al frontend
            echo json_encode([
                'tipo' => 'aprendiz',
                'datos' => $aprendiz,
                'imagen' => $rutaImagen
            ]);
            exit;
        }
         // Buscar en la tabla usuario
         $stmt = $pdo->prepare("SELECT u.IdUsuario , u.Nombre, u.Email, r.Rol
         FROM usuario u
         JOIN usuariorol ur ON u.IdUsuario = ur.IdUsuario
         JOIN rol r ON ur.IdRol = r.IdRol
         WHERE u.Documento = :documento");
        $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch();

        if ($usuario) {
        echo json_encode(['tipo' => 'usuario', 'datos' => $usuario]);
        exit;
        }

        // Si no se encuentra el aprendiz
        echo json_encode(['error' => 'No se encontró el aprendiz con el documento proporcionado.']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
}
