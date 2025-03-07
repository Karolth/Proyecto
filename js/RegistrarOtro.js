document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("formRegistro").addEventListener("submit", function (event) {
        event.preventDefault(); // Evita el envío tradicional del formulario

        let Referencia = document.getElementById("NombreOtro").value;
        let observaciones = document.getElementById("ObservacionesOtro").value;

        if (Referencia.trim() === "") {
            alert("El nombre del elemento es obligatorio.");
            return;
        }

        let formData = new FormData();
        formData.append("NombreOtro", Referencia);
        formData.append("ObservacionesOtro", observaciones);

        fetch("../php/RegistrarOtro.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("mensaje").innerText = data.mensaje;
            if (data.exito) {
                document.getElementById("formRegistro").reset();
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
