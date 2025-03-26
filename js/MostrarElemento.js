

document.addEventListener("DOMContentLoaded", function () {
    cargarMateriales();
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

// Cargar materiales al iniciar
// cargarMateriales();

// Opcional: Añadir botón de actualización
const botonActualizar = document.createElement('button');
botonActualizar.textContent = 'Actualizar Materiales';
botonActualizar.classList.add('btn', 'btn-primary', 'mt-3');
botonActualizar.addEventListener('click', cargarMateriales);

const contenedorMaterial = document.querySelector('.contenedorMaterial');
contenedorMaterial.appendChild(botonActualizar);