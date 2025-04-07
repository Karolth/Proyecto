function cargarHistorial() {
    fetch("../models/ModeloHistorial.php?action=obtenerHistorial")
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const tabla = document.querySelector("#tablaHistorial");
            tabla.innerHTML = ""; // Limpiar la tabla antes de insertar nuevos datos

            data.data.forEach(registro => {
                let fila = document.createElement('tr');
                fila.innerHTML = `
                    <td>${registro.NombreAprendiz}</td>
                    <td>${registro.Documento}</td>
                    <td>${registro.NombreMaterial}</td>
                    <td>${registro.Referencia}</td>
                    <td>${registro.Placa}</td>
                    <td>${registro.TipoVehiculo}</td>
                    <td>${registro.FechaHora}</td>
                    <td>${registro.movimiento}</td>
                `;
                tabla.appendChild(fila);
            });
        } else {
            console.error("Error al obtener historial:", data.message);
        }
    })
    .catch(error => console.error("Error en fetch:", error));
}

// Ejecutar la función al cargar la página
document.addEventListener("DOMContentLoaded", cargarHistorial);
