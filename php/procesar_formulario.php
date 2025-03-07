<?php
$servername = "localhost";
$username = "root"; // Reemplaza con tu usuario de MySQL
$password = ""; // Reemplaza con tu contraseña de MySQL
$dbname = "easycode";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$documento = htmlspecialchars(trim($_POST['Documento']));
$nombre = htmlspecialchars(trim($_POST['Nombre']));
$email = filter_var(trim($_POST['Email']), FILTER_SANITIZE_EMAIL);
$celular = htmlspecialchars(trim($_POST['Celular'] ?? ''));


if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Combinamos nombre y apellido en el campo 'Nombre' de la tabla 'Usuario'
    $nombreCompleto = $nombre . '';

    $sql = "INSERT INTO usuario (Documento, Nombre, Email, Celular) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $documento, $nombreCompleto, $email, $celular);

    if ($stmt->execute()) {
        echo   "Datos guardados correctamente en la tabla Usuario.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Correo electrónico inválido.";
}

$conn->close();
?>
