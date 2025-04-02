<?php
require '../config/conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagenes'])) {
    $imagenes = $_FILES['imagenes'];
    $total = count($imagenes['name']); // Cantidad de imágenes seleccionadas

    for ($i = 0; $i < $total; $i++) {
        $nombreArchivo = $imagenes['name'][$i];
        $tipoArchivo = $imagenes['type'][$i];
        $rutaTemporal = $imagenes['tmp_name'][$i];

        // Quitar la extensión del nombre del archivo
        $nombreSinExtension = pathinfo($nombreArchivo, PATHINFO_FILENAME);

        // Validaciones en el servidor
        $tiposValidos = ["image/jpeg", "image/png", "image/gif"];
        $tamañoMaximo = 2 * 1024 * 1024; // 2MB

        if (!in_array($tipoArchivo, $tiposValidos)) {
            echo "El archivo $nombreArchivo no es una imagen válida.<br>";
            continue;
        }

        if ($imagenes['size'][$i] > $tamañoMaximo) {
            echo "El archivo $nombreArchivo es demasiado grande (Máximo 2MB).<br>";
            continue;
        }

        // Leer el archivo como binario
        $contenido = file_get_contents($rutaTemporal);

        // Insertar en la base de datos con el nombre sin extensión
        $sql = "INSERT INTO imagenes_aprendiz (nombre_archivo, ruta_archivo) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$nombreSinExtension, $contenido])) {
            echo "Imagen '$nombreArchivo' subida con éxito.<br>";
        } else {
            echo "Error al subir la imagen '$nombreArchivo'.<br>";
        }
    }
} else {
    echo "No se recibieron imágenes.";
}


?>