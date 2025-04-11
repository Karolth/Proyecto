document.addEventListener("DOMContentLoaded", function () {
    cargarHistorialLogin();
});

function cargarHistorialLogin() {
    fetch("../controllers/HistorialLogin.php")
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tablaHistorialLogin tbody");
            tbody.innerHTML = "";

            if (data.success && data.data.length > 0) {
                data.data.forEach(item => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${item.Documento}</td>
                        <td>${item.Nombre}</td>
                        <td>${item.FechaHora}</td>
                        <td>${item.Rol}</td>
                    `;
                    tbody.appendChild(fila);
                });
            } else {
                const fila = document.createElement("tr");
                fila.innerHTML = `<td colspan="4" class="text-center">No hay registros de inicio de sesión.</td>`;
                tbody.appendChild(fila);
            }
        })
        .catch(error => {
            console.error("Error al cargar el historial:", error);
        });
}