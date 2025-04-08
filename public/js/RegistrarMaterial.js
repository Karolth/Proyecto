function registrarComputador() {
    // Recuperar ID de manera más robusta
    const idUsuario = localStorage.getItem("Id");
    const tipoUsuario = localStorage.getItem("Tipo");
    
    // Verificación de ID y tipo de usuario
    if (!idUsuario) {
        alert("Error: No se pudo recuperar el ID del usuario.");
        return;
    }
    
    const referencia = document.getElementById("referencia").value;
    const marca = document.getElementById("marca").value;
    const observaciones = document.getElementById("observaciones").value;
    const idTipoMaterial = "1"; // ID para Computador
    
    // Determinar el ID de aprendiz o usuario
    const idAprendiz = tipoUsuario === "aprendiz" ? idUsuario : null;
    const idUsuarioFinal = tipoUsuario === "usuario" ? idUsuario : null;
    
    fetch('../controllers/RegistrarMaterial.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `nombre=Computador&referencia=${referencia}&marca=${marca}&observaciones=${observaciones}&idTipoMaterial=${idTipoMaterial}&idUsuario=${idUsuarioFinal}&idAprendiz=${idAprendiz}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("mensaje").innerText = data;
        alert("Computador registrado correctamente");
    })
    .catch(error => console.error('Error:', error));
}

function registrarOtro() {

        // Recuperar ID y Tipo de localStorage
        const idUsuario = localStorage.getItem("Id");
        const tipoUsuario = localStorage.getItem("Tipo");

        if (!idUsuario) {
            alert("Error: No se ha iniciado sesión o no se pudo recuperar el ID.");
            return;
        }

        const nombre = document.getElementById("NombreOtro").value;
        const observaciones = document.getElementById("ObservacionesOtro").value;
        const idTipoMaterial = "2"; // ID para Computador


        if (nombre.trim() === "") {
            alert("El nombre del elemento es obligatorio.");
            return;
        }

          // Determinar el ID de aprendiz o usuario
        const idAprendiz = tipoUsuario === "aprendiz" ? idUsuario : null;
        const idUsuarioFinal = tipoUsuario === "usuario" ? idUsuario : null;

        fetch('../controllers/RegistrarOtro.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `nombre=otro&observaciones=${observaciones}&idTipoMaterial=${idTipoMaterial}&idUsuario=${idUsuarioFinal}&idAprendiz=${idAprendiz}`

        })
        .then(response => response.text())
    .then(data => {
        document.getElementById("mensaje").innerText = data;
        alert("Elemento registrado correctamente");
    })
    .catch(error => console.error('Error:', error));
}

function formulario(tipo) {
    // Verificar si hay un ID almacenado en localStorage (indica que se buscó un documento)
    const idUsuario = localStorage.getItem("Id");

    if (!idUsuario) {
        // Mostrar mensaje de advertencia si no se ha buscado un documento
        const mensajeError = document.createElement("div");
        mensajeError.classList.add("alert", "alert-danger", "text-center", "mt-3");
        mensajeError.innerText = "Debe buscar un documento antes de registrar un elemento.";
        document.getElementById("elementos").appendChild(mensajeError);

        // Eliminar el mensaje después de 3 segundos
        setTimeout(() => mensajeError.remove(), 3000);
        return;
    }

    // Mostrar el formulario correspondiente si se ha buscado un documento
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('computadorForm').style.display = tipo === "pc" ? 'block' : 'none';
    document.getElementById('automovilForm').style.display = tipo === "automovil" ? 'block' : 'none';
    document.getElementById('formOtro').style.display = tipo === "otro" ? 'block' : 'none';
}

function cerrarFormulario() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('computadorForm').style.display = 'none';
    document.getElementById('automovilForm').style.display = 'none';
    document.getElementById('formOtro').style.display = 'none';
}

// Seleccionar el botón de cerrar del modal de "Registrar Elemento"
const closeRegistrarElementoModal = document.querySelector('#registrar-elemento-modal .close-modal');

// Agregar el evento para recargar la tabla al cerrar el modal
if (closeRegistrarElementoModal) {
    closeRegistrarElementoModal.addEventListener('click', () => {
        recargarTabla(); // Llamar a la función para recargar la tabla
    });
}
function recargarTabla() {
    // Realizar una solicitud para obtener los datos actualizados
    fetch('../controllers/MostrarElemento.php') // Cambia esta URL por la correcta
        .then(response => response.json())
        .then(data => {
            // Seleccionar el cuerpo de la tabla
            const tbody = document.querySelector('#tbodyVehiculo'); // Cambia el selector si es necesario
            tbody.innerHTML = ''; // Limpiar el contenido actual de la tabla

            // Iterar sobre los datos y agregarlos a la tabla
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="checkbox-material">
                            <span class="slider"></span>
                        </label>
                    </td>
                    <td>${item.id}</td>
                    <td>${item.nombre}</td>
                    <td>${item.referencia}</td>
                    <td>${item.marca}</td>
                    <td>${item.tipoMaterial}</td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Error al recargar la tabla:', error));
}