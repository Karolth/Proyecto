document.addEventListener("DOMContentLoaded", cargarHistorial);

document.getElementById("searchForm").addEventListener("submit", function(event) {
    event.preventDefault();
    cargarHistorial(); // Llama a la función de carga de historial con filtros
});

function cargarHistorial() {
    fetch("../models/ModeloHistorial.php?action=obtenerHistorial")
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const tabla = document.querySelector("#tablaHistorial");
            tabla.innerHTML = ""; // Limpiar la tabla antes de insertar nuevos datos

            const searchTerm = document.getElementById("searchTerm").value.toLowerCase();
            const filterField = document.getElementById("filterField").value;

            data.data.forEach(registro => {
                let shouldDisplay = false;

                // Aplicar filtro basado en el campo seleccionado
                if (filterField === "Nombre" && registro.NombreAprendiz.toLowerCase().includes(searchTerm)) {
                    shouldDisplay = true;
                } else if (filterField === "Documento" && registro.Documento.toLowerCase().includes(searchTerm)) {
                    shouldDisplay = true;
                } else if (filterField === "Material" && registro.NombreMaterial.toLowerCase().includes(searchTerm)) {
                    shouldDisplay = true;
                } else if (filterField === "Referencia" && registro.Referencia.toLowerCase().includes(searchTerm)) {
                    shouldDisplay = true;
                } else if (filterField === "Placa" && registro.Placa.toLowerCase().includes(searchTerm)) {
                    shouldDisplay = true;
                } else if (filterField === "TipoVehiculo" && registro.TipoVehiculo.toLowerCase().includes(searchTerm)) {
                    shouldDisplay = true;
                } else if (filterField === "") {
                    // Si no se selecciona un filtro, mostrar todos los registros que contengan el término de búsqueda
                    shouldDisplay = Object.values(registro).some(value => 
                        value.toString().toLowerCase().includes(searchTerm)
                    );
                }

                // Si el registro debe mostrarse, crear una fila en la tabla
                if (shouldDisplay) {
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
                }
            });
        } else {
            console.error("Error al obtener historial:", data.message);
        }
    })
    .catch(error => console.error("Error en fetch:", error));
}