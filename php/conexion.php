<?php
$host = '127.0.0.1'; //Direccion de la base de datos
$db =  'easycode'; //Nombre de la base de datos
$user = 'root'; //Usuario por defecto de XAMPP (puede variar)
$pass = '';  //Contraseña (por defecto, está vacía en XAMPP)
$charset = 'utf8mb4';

$conexion = "mysql:host=$host;dbname=$db;charset=$charset"; //Data Source Name
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Habilitar excepciones en errores- modo de manejar errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Devolver datos como aray asociativo
];
try {
    $pdo = new PDO($conexion,$user,$pass,$options);

        // echo "Conexion exitosa a la base de datos";
} catch (PDOException $e) {
        // echo "Error en la conexión: " . $e->getMessage();
}
