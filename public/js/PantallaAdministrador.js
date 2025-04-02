function mostrarInformacionAdmin() {
    // Aquí iría la lógica para obtener la información del usuario registrado
    // Por ejemplo, puedes obtenerla de una base de datos o un objeto JavaScript
    const adminInfo = {
        nombre: "Nombre del Administrador",
        apellidos: "Apellidos del Administrador",
        documento: "123456789",
        email: "admin@example.com",
        celular: "123-456-7890"
    };

    // Mostrar la información en los elementos span o div
    document.getElementById("nombreAdmin").textContent = adminInfo.nombre;
    document.getElementById("apellidosAdmin").textContent = adminInfo.apellidos;
    document.getElementById("documentoAdmin").textContent = adminInfo.documento;
    document.getElementById("emailAdmin").textContent = adminInfo.email;
    document.getElementById("celularAdmin").textContent = adminInfo.celular;

    // Ocultar el botón de administrador
    document.querySelector('.admin-button').style.display = 'none';

    // Mostrar el overlay
    document.getElementById("overlay").style.display = "flex";
}

function cerrarInformacionAdmin() {
    // Mostrar el botón de administrador
    document.querySelector('.admin-button').style.display = 'block';

    // Ocultar el overlay
    document.getElementById("overlay").style.display = "none";
}