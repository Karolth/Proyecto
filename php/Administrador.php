<?php
include 'conexion.php'; // Asegúrate de que la ruta sea correcta

if (!isset($pdo)) {
    die("Error: No se pudo conectar a la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movimiento = $_POST["movimiento"]; // No hace falta escape con PDO

    try {
        $stmt = $pdo->prepare("INSERT INTO movimiento (Movimiento, FechaHora ) VALUES (:movimiento, NOW())");
        $stmt->bindParam(':movimiento', $movimiento, PDO::PARAM_STR);
        $stmt->execute();

        echo "Registro de $movimiento guardado correctamente.";
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
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

        // Si no se encuentra el aprendiz
        echo json_encode(['error' => 'No se encontró el aprendiz con el documento proporcionado.']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
}
