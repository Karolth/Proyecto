document.addEventListener("DOMContentLoaded", function () {
    const tablaHistorial = document.getElementById("tablaHistorial");

    // Función para cargar el historial
    function cargarHistorial() {
        fetch("../controllers/Historial.php?action=getHistorial")
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error al obtener los datos");
                }
                return response.json();
            })
            .then(data => {
                tablaHistorial.innerHTML = ""; // Limpiar la tabla
                data.forEach(item => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${item.Nombre}</td>
                        <td>${item.Documento}</td>
                        <td>${item.NombreMaterial || "N/A"}</td>
                        <td>${item.Referencia || "N/A"}</td>
                        <td>${item.Placa || "N/A"}</td>
                        <td>${item.TipoVehiculo || "N/A"}</td>
                        <td>${item.FechaHora}</td>
                        <td>${item.Movimiento}</td>
                    `;
                    tablaHistorial.appendChild(fila);
                });
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }

    // Cargar el historial al cargar la página
    cargarHistorial();
});