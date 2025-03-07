function formulario(tipo) {
    const overlay = document.getElementById('overlay');
    const formularioComputador = document.getElementById('computadorForm');
    const formularioAutomovil = document.getElementById('automovilForm');
    const formulariOtro = document.getElementById('formOtro');

    overlay.style.display = 'block';

    formularioComputador.style.display = tipo === "pc" ? 'block' : 'none';
    formularioAutomovil.style.display = tipo === "automovil" ? 'block' : 'none';
    formulariOtro.style.display = tipo === "otro" ? 'block' : 'none';
}

function cerrarFormulario() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('computadorForm').style.display = 'none';
    document.getElementById('automovilForm').style.display = 'none';
    document.getElementById('formOtro').style.display = 'none';
}



document.getElementById("formPC").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    
    let referencia = document.getElementById("referencia").value.trim();
    let marca = document.getElementById("marca").value.trim();
    let observaciones = document.getElementById("observaciones").value.trim();

    if (referencia === "" || marca === "") {
        alert("El referencia y la marca son obligatorios.");
        return;
    }

    let formData = new FormData();
    formData.append("referencia", referencia);
    formData.append("marca", marca);
    formData.append("observaciones", observaciones);

    fetch("../php/RegistrarComputador.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.mensaje);
        if (data.exito) {
            document.getElementById("formPC").reset();
            cerrarFormulario(); // Cierra el formulario si todo salió bien
        }
    })
    .catch(error => console.error("Error en la solicitud:", error));
});
