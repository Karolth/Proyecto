let btn = document.querySelector("#btn");
let sidebar = document.querySelector(".sidebar");

btn.onclick = function () {
    sidebar.classList.toggle("active");
}

function registrar(tipo) {
    // Obtener el ID del usuario o aprendiz desde localStorage
    const id = localStorage.getItem("Id");
    const tipoUsuario = localStorage.getItem("Tipo");

    if (!id || !tipoUsuario) {
        alert("No se encontró información del usuario o aprendiz. Por favor, realice una búsqueda primero.");
        return;
    }

    // Enviar el ID y el tipo de movimiento al backend
    fetch('../controllers/Administrador.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `movimiento=${tipo}&id=${id}&tipoUsuario=${tipoUsuario}`
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

    fetch("../controllers/Administrador.php?documento=" + documento)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                mensajeBusqueda.innerHTML = data.error;
                return;
            }

            let resultadoHTML = "<ul>";

            if (data.tipo === "aprendiz") {
                localStorage.setItem("Id", data.datos.IdAprendiz);
                localStorage.setItem("Tipo", data.tipo);
                resultadoHTML += `<div style=" font-size: 14px; line-height: 1.4;">`
                resultadoHTML += `<img src="${data.imagen}" alt="Foto del aprendiz" style="display: block; margin: 0 auto 15px auto; border-radius: 10px; width: 150px;">`;
                resultadoHTML += `<p><strong>Nombre:</strong> ${data.datos.Nombre}</p>`;
                resultadoHTML += `<p><strong>Rol:</strong> Aprendiz</p>`;
                resultadoHTML += `<p><strong>RH:</strong> ${data.datos.RH}</p>`;
                resultadoHTML += `<p><strong>Tipo de Programa:</strong> ${data.datos.TipoPrograma}</p>`;
                resultadoHTML += `<p><strong>Programa:</strong> ${data.datos.Programa}</p>`;
                resultadoHTML += `</div>`
            } else if (data.tipo === "usuario") {
                localStorage.setItem("Id", data.datos.IdUsuario);
                localStorage.setItem("Tipo", data.tipo);

                resultadoHTML += `<p><strong>Nombre:</strong> ${data.datos.Nombre}</p>`;
                resultadoHTML += `<p><strong>Rol:</strong> ${data.datos.Rol}</p>`;
                resultadoHTML += `<p><strong>Email:</strong> ${data.datos.Email}</p>`;
            }

            // Agregar el estado del movimiento (Entrada/Salida)
            resultadoHTML += `<p  style=" font-size: 14px; line-height: 1.4;"><strong>Último Movimiento:</strong> ${data.movimiento}</p>`;

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
    const tbody = document.querySelector("#Material tbody");
    tbody.innerHTML = ""; // Limpiar filas existentes
    const idUsuario = localStorage.getItem("Id");
    const tipoUsuario = localStorage.getItem("Tipo");

    if (!idUsuario) {
        console.error("No se encontró ID de usuario");
        return;
    }

    fetch(`../controllers/MostrarElemento.php?idUsuario=${idUsuario}&tipoUsuario=${tipoUsuario}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error al obtener materiales:", data.error);
                return;
            }

            

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">No se han registrado materiales</td>
                    </tr>
                `;
                return;
            }

            data.forEach(material => {
                const checked = material.Estado === "Entrada" ? "checked" : ""; // Estado basado en la base de datos

                const fila = `
                    <tr>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="checkbox-material" ${checked} onchange="registrarMovimientoMaterial(this, '${material.IdMaterial}')">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td><span class="etiqueta id-movimiento-material">${material.IdMaterial}</span></td>
                        <td><span class="etiqueta nombre">${material.Nombre}</span></td>
                        <td><span class="etiqueta referencia">${material.Referencia}</span></td>
                        <td><span class="etiqueta marca">${material.Marca}</span></td>
                        <td><span class="etiqueta materia">${material.Tipo}</span></td>
                    </tr>

                `;
                tbody.innerHTML += fila;
            });
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">Error al cargar materiales</td>
                </tr>
            `;
        });
}

     
function cargarVehiculos() {
    const tbodyV = document.getElementById("tbodyVehiculo");

    tbodyV.innerHTML = ""; 
    const idUsuario = localStorage.getItem("Id");
    const tipoUsuario = localStorage.getItem("Tipo");
    const tipoConsulta = "vehiculo"; // Cambia esto si es necesario

    if (!idUsuario) {
        console.error("No se encontró ID de usuario");
        return;
    }

    fetch(`../controllers/MostrarElemento.php?idUsuario=${idUsuario}&tipoUsuario=${tipoUsuario}&tipoConsulta=${tipoConsulta}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error al obtener materiales:", data.error);
                return;
            }

            // Limpiar filas existentes

            if (data.length === 0) {
                tbodyV.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">No se han registrado materiales</td>
                </tr>
            `;
                return;
            }

            // Generar filas para cada vehículo
            data.vehiculo.forEach(vehiculo => {
                const checked = vehiculo.Estado === "Entrada" ? "checked" : ""; // Estado basado en la base de datos

                const fila = `
                    <tr>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="checkbox-vehiculo" ${checked} onchange="registrarMovimientoVehiculo(this, '${vehiculo.IdVehiculo}')">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td><span class="etiqueta id-movimiento-vehiculo">${vehiculo.IdVehiculo}</span></td>
                        <td><span class="etiqueta placa">${vehiculo.Placa}</span></td>
                        <td><span class="etiqueta tipo">${vehiculo.Tipo}</span></td>
                    </tr>
            `;
                tbodyV.innerHTML += fila;
            });
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            const filaError = `
            <tr>
                <td colspan="3" class="text-center">Error al cargar vehículos</td>
            </tr>
        `;
            tbodyV.innerHTML = filaError;
        });
}

async function registrarMovimientosAmbos() {
    const checkboxesMateriales = document.querySelectorAll(".checkbox-material:checked");
    const checkboxesVehiculos = document.querySelectorAll(".checkbox-vehiculo:checked");

    const idMaterial = Array.from(checkboxesMateriales).map(checkbox => {
        const fila = checkbox.closest('tr');
        return fila.querySelector('.id-movimiento-material')?.textContent;
    }).filter(id => id !== null);

    const idVehiculo = Array.from(checkboxesVehiculos).map(checkbox => {
        const fila = checkbox.closest('tr');
        return fila.querySelector('.id-movimiento-vehiculo')?.textContent;
    }).filter(id => id !== null);

    if (idMaterial.length === 0 && idVehiculo.length === 0) {
        alert("Por favor, seleccione al menos un material o vehículo.");
        return;
    }

    try {
        // Crear el cuerpo de la solicitud
        const body = {
            materiales: idMaterial,
            vehiculos: idVehiculo,
        };

        console.log("Enviando datos al backend:", body);

        // Realizar un único llamado al backend
        const response = await fetch('../controllers/MovimientoElementos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
        } else {
            alert("Error: " + data.message);
        }
    } catch (error) {
        console.error("Error al registrar los movimientos:", error);
        alert("Error al registrar los movimientos.");
    }
}



window.cargarMateriales = cargarMateriales;
window.cargarVehiculos = cargarVehiculos;



function recargarTabla(tipo) {
    if (tipo === "material" || tipo === "ambos") {
        cargarMateriales();
    }
    
    if (tipo === "vehiculo" || tipo === "ambos") {
        cargarVehiculos();
    }
}

window.addEventListener('message', function(event) {
    if (event.data.action === "recargarTabla") {
        recargarTabla(event.data.tipo);
    }
});