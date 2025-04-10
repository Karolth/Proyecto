<?php
require_once '../config/conexion.php'; // Incluir el archivo de conexión

class HistorialModel {
    public function fetchHistorial() {
        global $pdo; // Usar la variable $pdo definida en conexion.php

        $query = "
            SELECT
                DATE(m.FechaHora) AS Fecha,
                a.Nombre AS Nombre,
                'Aprendiz' AS TipoActor,
                a.Documento,
                m.FechaHora,
                m.Movimiento AS Movimiento,
                mat.Nombre AS NombreMaterial,
                mat.Referencia,
                v.Placa,
                tv.Tipo AS TipoVehiculo
            FROM movimiento m
            JOIN aprendiz a ON a.idAprendiz = m.idAprendiz
            LEFT JOIN movimientoMaterial mm ON m.idMovimiento = mm.idMovimiento
            LEFT JOIN material mat ON mm.idMaterial = mat.idMaterial
            LEFT JOIN movimientoVehiculo mv ON m.idMovimiento = mv.idMovimiento
            LEFT JOIN vehiculo v ON mv.idVehiculo = v.idVehiculo
            LEFT JOIN tipovehiculo tv ON v.idTipoVehiculo = tv.idTipoVehiculo

            UNION ALL

            SELECT
                DATE(m.FechaHora) AS Fecha,
                u.Nombre AS Nombre,
                'Usuario' AS TipoActor,
                u.Documento,
                m.FechaHora,
                m.Movimiento AS Movimiento,
                mat.Nombre AS NombreMaterial,
                mat.Referencia,
                v.Placa,
                tv.Tipo AS TipoVehiculo
            FROM movimiento m
            JOIN usuario u ON u.idUsuario = m.idUsuario
            LEFT JOIN movimientoMaterial mm ON m.idMovimiento = mm.idMovimiento
            LEFT JOIN material mat ON mm.idMaterial = mat.idMaterial
            LEFT JOIN movimientoVehiculo mv ON m.idMovimiento = mv.idMovimiento
            LEFT JOIN vehiculo v ON mv.idVehiculo = v.idVehiculo
            LEFT JOIN tipovehiculo tv ON v.idTipoVehiculo = tv.idTipoVehiculo

            ORDER BY Fecha DESC, FechaHora DESC;
        ";

        $stmt = $pdo->prepare($query); // Usar $pdo para preparar la consulta
        $stmt->execute();
        $resultados = $stmt->fetchAll(); // Obtener los resultados

        // Reemplazar valores null o vacíos por "-"
        foreach ($resultados as &$fila) {
            foreach ($fila as $key => $value) {
                if (is_null($value) || $value === '') {
                    $fila[$key] = '-';
                }
            }
        }

        return $resultados;
    }
}
