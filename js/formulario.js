document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#usuarioForm");

    form.addEventListener("submit", function (event) {
        let isValid = true;

        const nombre = document.getElementById("Nombre").value.trim();
        const documento = document.getElementById("Documento").value.trim();
        const email = document.getElementById("Email").value.trim();
        const celular = document.getElementById("Celular").value.trim();
        const rol = document.getElementById("rol").value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const celularRegex = /^[0-9]{10}$/;
        const documentoRegex = /^[0-9]+$/;

        if (nombre === "" || documento === "" || email === "" || celular === "" || rol === "") {
            alert("Todos los campos son obligatorios.");
            isValid = false;
        }

        if (!emailRegex.test(email)) {
            alert("Ingrese un correo válido.");
            isValid = false;
        }

        if (!celularRegex.test(celular)) {
            alert("El celular debe tener 10 dígitos.");
            isValid = false;
        }

        if (!documentoRegex.test(documento)) {
            alert("El documento solo puede contener números.");
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});
