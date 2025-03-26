document.addEventListener("DOMContentLoaded", function () {
    cargarMateriales();
    cargarVehiculo(); // Llamar a la función para cargar vehículos al iniciar
});

function cargarMateriales() {
    const idUsuario = localStorage.getItem("Id");
    const tipoUsuario = localStorage.getItem("Tipo");

    if (!idUsuario) {
        console.error("No se encontró ID de usuario");
        return;
    }

    fetch(`../php/MostrarElemento.php?idUsuario=${idUsuario}&tipoUsuario=${tipoUsuario}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error al obtener materiales:", data.error);
                return;
            }

            const tbody = document.querySelector("#Material tbody");
            tbody.innerHTML = ""; // Limpiar filas existentes

            if (data.materiales.length === 0) {
                const filaVacia = `
                    <tr>
                        <td colspan="5" class="text-center">No se han registrado materiales</td>
                    </tr>
                `;
                tbody.innerHTML = filaVacia;
                return;
            }

            // Generar filas para cada material
            data.materiales.forEach(material => {
                const fila = `
                    <tr>
                        <td><span class="etiqueta id-movimiento-material">${material.idMaterial || 'N/A'}</span></td>
                        <td><span class="etiqueta Ingreso">Ingresado</span></td>
                        <td><span class="etiqueta referencia">${material.referencia || material.nombre || 'N/A'}</span></td>
                        <td><span class="etiqueta marca">${material.marca || 'N/A'}</span></td>
                        <td><span class="etiqueta materia">${material.tipo_material || 'N/A'}</span></td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            const tbody = document.querySelector("#Material tbody");
            const filaError = `
                <tr>
                    <td colspan="5" class="text-center">Error al cargar materiales</td>
                </tr>
            `;
            tbody.innerHTML = filaError;
        });
}

function cargarVehiculo() {
    const idUsuario = localStorage.getItem("Id");
    const tipoUsuario = localStorage.getItem("Tipo");

    if (!idUsuario) {
        console.error("No se encontró ID de usuario");
        return;
    }

    fetch(`../php/MostrarVehiculo.php?idUsuario=${idUsuario}&tipoUsuario=${tipoUsuario}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error al obtener vehículos:", data.error);
                return;
            }

            const tbody = document.querySelector("#Vehiculo tbody");
            tbody.innerHTML = ""; // Limpiar filas existentes

            if (data.vehiculos.length === 0) {
                const filaVacia = `
                    <tr>
                        <td colspan="3" class="text-center">No se han registrado vehículos</td>
                    </tr>
                `;
                tbody.innerHTML = filaVacia;
                return;
            }

            // Generar filas para cada vehículo
            data.vehiculos.forEach(vehiculo => {
                const fila = `
                    <tr>
                        <td><span class="etiqueta id-movimiento-vehiculo">${vehiculo.idVehiculo || 'N/A'}</span></td>
                        <td><span class="etiqueta placa">${vehiculo.placa || 'N/A'}</span></td>
                        <td><span class="etiqueta tipo">${vehiculo.tipoVehiculo || 'N/A'}</span></td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            const tbody = document.querySelector("#datosVehiculos");
            const filaError = `
                <tr>
                    <td colspan="3" class="text-center">Error al cargar vehículos</td>
                </tr>
            `;
            tbody.innerHTML = filaError;
        });
}

// Opcional: Añadir botón de actualización para vehículos
const botonActualizarVehiculos = document.createElement('button');
botonActualizarVehiculos.textContent = 'Actualizar Vehículos';
botonActualizarVehiculos.classList.add('btn', 'btn-primary', 'mt-3');
botonActualizarVehiculos.addEventListener('click', cargarVehiculos);

const contenedorVehiculo = document.querySelector('.contenedorVehiculo');
contenedorVehiculo.appendChild(botonActualizarVehiculos);