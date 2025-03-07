document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        let isValid = true;

        // Obtener los valores de los campos
        const nombre = document.getElementById("Nombre").value.trim();
        const documento = document.getElementById("Documento").value.trim();
        const email = document.getElementById("Email").value.trim();
        const celular = document.getElementById("Celular").value.trim();

        // Expresiones regulares para validación
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const celularRegex = /^[0-9]{10}$/; // Solo números, 10 dígitos
        const documentoRegex = /^[0-9]+$/; // Solo números

        // Validaciones
        if (nombre === "" || documento === "" || email === "" || celular === "") {
            alert("Todos los campos son obligatorios.");
            isValid = false;
        }

        if (!emailRegex.test(email)) {
            alert("Por favor, ingrese un correo electrónico válido.");
            isValid = false;
        }

        if (!celularRegex.test(celular)) {
            alert("El número de celular debe tener 10 dígitos numéricos.");
            isValid = false;
        }

        if (!documentoRegex.test(documento)) {
            alert("El documento debe contener solo números.");
            isValid = false;
        }

        // Si alguna validación falla, detener el envío del formulario
        if (!isValid) {
            event.preventDefault();
        }
    });
});

