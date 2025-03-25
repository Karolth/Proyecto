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
    
    fetch('../php/RegistrarMaterial.php', {
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

        fetch('../php/RegistrarOtro.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `nombre=otro&observaciones=${observaciones}&idTipoMaterial=${idTipoMaterial}&idUsuario=${idUsuarioFinal}&idAprendiz=${idAprendiz}`

        })
        .then(response => response.text())
    .then(data => {
        document.getElementById("mensaje").innerText = data;
        alert("Computador registrado correctamente");
    })
    .catch(error => console.error('Error:', error));
}


function formulario(tipo) {
    const overlay = document.getElementById('overlay');
    const formularioComputador = document.getElementById('computadorForm');
    const formularioAutomovil = document.getElementById('automovilForm');
    const formulariOtro = document.getElementById('formOtro');

    overlay.style.display = 'block';
    formularioComputador.style.display = tipo === "pc" ? 'block' : 'none';
    formularioAutomovil.style.display = tipo === "automovil" ? 'block' : 'none';
    formulariOtro.style.display = tipo === "otro" ? 'block' : 'none';
}

function cerrarFormulario() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('computadorForm').style.display = 'none';
    document.getElementById('automovilForm').style.display = 'none';
    document.getElementById('formOtro').style.display = 'none';
}
