
let btn = document.querySelector("#btn");
let sidebar = document.querySelector(".sidebar");

btn.onclick = function () {
    sidebar.classList.toggle("active");
}

function registrar(tipo) {
    fetch('../php/Administrador.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `movimiento=${tipo}`
    })
        .then(response => response.text())
        .then(data => alert(data))
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function () {
    let btn = document.querySelector("#btn");
    let sidebar = document.querySelector(".sidebar");

    if (btn && sidebar) {
        btn.onclick = function () {
            sidebar.classList.toggle("active");
        };
    } else {
        console.error("Sidebar or button element not found.");
    }
});


function buscarDocumento() {
    var documento = document.getElementById("buscarDocumento").value;
    var mensajeBusqueda = document.getElementById("mensaje-busqueda");

    if (documento.trim() === "") {
        mensajeBusqueda.innerHTML = "Por favor, ingrese un documento.";
        return;
    }

    fetch("../php/Administrador.php?documento=" + documento)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                mensajeBusqueda.innerHTML = data.error;
                return;
            }
            let resultadoHTML = "<ul>";

            if (data.tipo === "aprendiz") {
                // Guardar datos de aprendiz
                localStorage.setItem("Id", data.datos.IdAprendiz);
                localStorage.setItem("Tipo", data.tipo);

                // Desplegar información
                resultadoHTML += `<p><strong>Nombre:</strong> ${data.datos.Nombre}</p>`;
                resultadoHTML += `<p><strong>Rol:</strong> Aprendiz</p>`;
                resultadoHTML += `<p><strong>RH:</strong> ${data.datos.RH}</p>`;
                resultadoHTML += `<p><strong>Tipo de Programa:</strong> ${data.datos.TipoPrograma}</p>`;
                resultadoHTML += `<p><strong>Programa:</strong> ${data.datos.Programa}</p>`;

            } else if (data.tipo === "usuario") {
                // Guardar datos de usuario
                localStorage.setItem("Id", data.datos.IdUsuario);
                localStorage.setItem("Tipo", data.tipo);

                // Desplegar información
                resultadoHTML += `<p><strong>Nombre:</strong> ${data.datos.Nombre}</p>`;
                resultadoHTML += `<p><strong>Rol:</strong> ${data.datos.Rol}</p>`;
                resultadoHTML += `<p><strong>Email:</strong> ${data.datos.Email}</p>`;
            }


            resultadoHTML += "</ul>";
            mensajeBusqueda.innerHTML = resultadoHTML;
            cargarMateriales();
            cargarVehiculos();
        })
        .catch(error => {
            mensajeBusqueda.innerHTML = "Error en la búsqueda.";
            console.error("Error:", error);
        });
}


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

            if (data.length === 0) {
                const filaVacia = `
                    <tr>
                        <td colspan="5" class="text-center">No se han registrado materiales</td>
                    </tr>
                `;
                tbody.innerHTML = filaVacia;
                return;
            }

            // Generar filas para cada material
            data.forEach(material => {
                const fila = `
                    <tr>
                        <td><span class="etiqueta id-movimiento-material">${material.IdMaterial}</span></td>
                        <td><span class="etiqueta referencia">${material.Referencia  }</span></td>
                        <td><span class="etiqueta marca">${material.Marca}</span></td>
                        <td><span class="etiqueta materia">${material.Tipo }</span></td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            // const tbody = document.querySelector("#Material tbody");
            const filaError = `
                <tr>
                    <td colspan="5" class="text-center">Error al cargar materiales</td>
                </tr>
            `;
            tbody.innerHTML = filaError;
        });
}

function cargarVehiculos() {
    const idUsuario = localStorage.getItem("Id");
    const tipoUsuario = localStorage.getItem("Tipo");

    if (!idUsuario) {
        console.error("No se encontró ID de usuario");
        return;
    }

    fetch(`../php/MostrarElemento.php?idUsuario=${idUsuario}&tipoUsuario=${tipoUsuario}&tipoConsulta=vehiculo`)
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data); // Verifica qué datos devuelve el servidor
        if (data.error) {
            console.error("Error al obtener vehículos:", data.error);
            return;
        }

        const tbody = document.querySelector("#Vehiculo tbody");
        tbody.innerHTML = ""; // Limpiar filas existentes

        if (data.vehiculo.length === 0) {
            const filaVacia = `
                <tr>
                    <td colspan="3" class="text-center">No se han registrado vehículos</td>
                </tr>
            `;
            tbody.innerHTML = filaVacia;
            return;
        }

        // Generar filas para cada vehículo
        data.vehiculo.forEach(vehiculo => {
            const fila = `
                <tr>
                    <td><span class="etiqueta id-movimiento-vehiculo">${vehiculo.IdVehiculo}</span></td>
                    <td><span class="etiqueta placa">${vehiculo.Placa}</span></td>
                    <td><span class="etiqueta tipo">${vehiculo.Tipo}</span></td>
                </tr>
            `;
            tbody.innerHTML += fila;
        });
    })
    .catch(error => {
        console.error("Error en la solicitud:", error);
        const filaError = `
            <tr>
                <td colspan="3" class="text-center">Error al cargar vehículos</td>
            </tr>
        `;
        tbody.innerHTML = filaError;
    });
}
