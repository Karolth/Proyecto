document.addEventListener("DOMContentLoaded", function () {
    fetch("http://localhost/Easycode/php/RegistrarVehiculo.php?action=cargarTipo")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let selectTipo = document.getElementById("tipoVehiculo");
                selectTipo.innerHTML = "<option value=''>Seleccione un tipo</option>";

                data.TipoVehiculo.forEach(tipo => {
                    let option = document.createElement("option");
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
function Vehiculo() {
    // event.preventDefault(); // Evita la recarga del formulario

    const action = "Vehiculo";
    const Placa = document.getElementById("Placa").value;
    const IdTipoVehiculo = document.getElementById("tipoVehiculo").value; // Corregido el ID

    if (!Placa || !IdTipoVehiculo) {
        alert("Por favor, complete todos los campos.");
        return;
    }

    fetch("../php/RegistrarVehiculo.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ action, Placa, IdTipoVehiculo })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Error en la respuesta del servidor");
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert("¡Vehículo registrado exitosamente!");
        } else {
            alert("Error: " + data.mensaje);
        }
    })
    .catch(error => {
        alert("Error en el registro del vehículo");
        console.error("Error:", error);
    });
}