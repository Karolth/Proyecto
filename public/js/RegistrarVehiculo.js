document.addEventListener("DOMContentLoaded", function () {
    fetch("../controllers/RegistrarVehiculo.php?action=cargarTipo")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const selectTipo = document.getElementById("tipoVehiculo");
                selectTipo.innerHTML = "<option value=''>Seleccione un tipo</option>";

                data.TipoVehiculo.forEach(tipo => {
                    const option = document.createElement("option");
                    option.value = tipo.IdTipoVehiculo;
                    option.text = tipo.Tipo;
                    selectTipo.appendChild(option);
                });
            } else {
                console.error("Error en la respuesta:", data.message);
            }
        })
        .catch(error => console.error("Error en la petición:", error));
});

function registrarVehiculo() {
    
    const idUsuario = localStorage.getItem("Id");
    const tipoUsuario = localStorage.getItem("Tipo");

    if (!idUsuario) {
        alert("Error: No se ha iniciado sesión o no se pudo recuperar el ID.");
        return;
    }

    const placa = document.getElementById("Placa").value;
    const idTipoVehiculo = document.getElementById("tipoVehiculo").value;

    if (!placa.trim()  === "" ) {
        alert("Por favor, complete todos los campos.");
        return;
    }

    // Determinar el ID de aprendiz o usuario
    const idAprendiz = tipoUsuario === "aprendiz" ? idUsuario : null;
    const idUsuarioFinal = tipoUsuario === "usuario" ? idUsuario : null;

    fetch("../controllers/RegistrarVehiculo.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=Vehiculo&placa=${placa}&idTipoVehiculo=${idTipoVehiculo}&idUsuario=${idUsuarioFinal}&idAprendiz=${idAprendiz}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("mensaje").innerText = data;
        alert("¡Vehículo registrado exitosamente!");
    })
    .catch(error => {
        alert("Error en el registro del vehículo");
        console.error("Error:", error);
    });
}