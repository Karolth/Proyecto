<?php
include 'conexion.php'; // Incluye tu archivo de conexión

header('Content-Type: application/json');

if(isset($_GET['documento'])) {
    $documento = $_GET['documento'];
//    buscar en tabla aprendiz sino encuetra se busca en usuario
    try {
        // Consulta para obtener todos los datos del aprendiz
        $query = "SELECT 
                    a.IdAprendiz, 
                    a.Nombre as Nombre, 
                    a.Documento, 
                    a.RH,
                    f.NumFicha,
                    f.Jornada,
                    p.Nombre as NombreP,
                    p.Version,
                    p.Fecha as FechaP,
                    tp.TipoPrograma
                FROM aprendiz a
                INNER JOIN fichaaprendiz fa ON a.IdAprendiz = fa.IdAprendiz
                INNER JOIN ficha f ON fa.IdFicha = f.IdFicha
                INNER JOIN programa p ON f.IdPrograma = p.IdPrograma
                INNER JOIN tipoprograma tp ON p.IdTipoPrograma = tp.IdTipoPrograma
                WHERE a.Documento = :documento";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute(['documento' => $documento]);
        
        $resultado = $stmt->fetch();
        
        if($resultado) {
            echo json_encode([
                'status' => 'success',
                'data' => $resultado
            ]);
        } else {
            // buscar en tabla usuario  CON CAMPOS (nombre y documento)
            $query = "SELECT * 
                FROM usuario
                WHERE Documento = :documento";

            $stmt = $pdo->prepare($query);
            $stmt->execute(['documento' => $documento]);

            $resultado = $stmt->fetch();

            if($resultado) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $resultado
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontró ningún aprendiz con ese documento'
                ]);
            }
        }
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error en la consulta: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No se proporcionó un documento para buscar'
    ]);
}

//     try {
//         // Consulta para obtener todos los datos del aprendiz
//         $query = "SELECT 
//                     a.IdAprendiz, 
//                     a.Nombre as Nombre, 
//                     a.Documento, 
//                     a.RH,
//                     f.NumFicha,
//                     f.Jornada,
//                     p.Nombre as NombreP,
//                     p.Version,
//                     p.Fecha as FechaP,
//                     tp.TipoPrograma
//                 FROM aprendiz a
//                 INNER JOIN fichaaprendiz fa ON a.IdAprendiz = fa.IdAprendiz
//                 INNER JOIN ficha f ON fa.IdFicha = f.IdFicha
//                 INNER JOIN programa p ON f.IdPrograma = p.IdPrograma
//                 INNER JOIN tipoprograma tp ON p.IdTipoPrograma = tp.IdTipoPrograma
//                 WHERE a.Documento = :documento";
        
//         $stmt = $pdo->prepare($query);
//         $stmt->execute(['documento' => $documento]);
        
//         $resultado = $stmt->fetch();
        
//         if($resultado) {
//             echo json_encode([
//                 'status' => 'success',
//                 'data' => $resultado
//             ]);
//         } else {
//             echo json_encode([
//                 'status' => 'error',
//                 'message' => 'No se encontró ningún aprendiz con ese documento'
//             ]);
//         }
//     } catch(PDOException $e) {
//         echo json_encode([
//             'status' => 'error',
//             'message' => 'Error en la consulta: ' . $e->getMessage()
//         ]);
//     }
// } else {
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'No se proporcionó un documento para buscar'
//     ]);
// }
?>