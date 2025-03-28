//const usuarioValido ="admin";
//const passValida ="1234";
let intentosRestantes =3;

function verificarLogin() {
    let action = "login";
    let Documento = document.getElementById("Documento").value;
    let password = document.getElementById("password").value;
    let docError = document.getElementById("docError");
    let passError = document.getElementById("passError");
    let message = document.getElementById("message");

    docError.textContent = "";
    passError.textContent = "";
    message.textContent = "";

    let hasError = false;


    // Validación de campos vacíos
    if (Documento === "") {
        docError.textContent = "El documento es obligatorio";
        hasError = true;
    }
    if (password === "") {
        passError.textContent = "La contraseña es obligatoria";
        hasError = true;
    }

    fetch("../php/usuariosIS.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ action, Documento, password })
    })
    .then(response => response.json())
    .then(data => {
        message.style.color = data.success ? "green" : "red";
        message.textContent = data.message;

        if (data.success) {
            setTimeout(() => {
                window.location.href = "../html/Administrador.html";
            }, 1000);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        message.style.color = "red";
        message.textContent = "Error al iniciar sesión";
    });
}


function mostrarPerfil() {
    const action = "getPerfil";

    fetch("../php/usuariosIS.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"  
        },
        body: JSON.stringify({ action })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById("perfilNombre").value = data.Nombre;
            document.getElementById("perfilDocumento").value = data.Documento;
            document.getElementById("perfilEmail").value = data.Email;
            document.getElementById("perfilTelefono").value = data.Telefono;

            // Mostrar modal después de obtener los datos
            document.getElementById("perfilDatos").style.display = "block"; 
        } else {
            alert("Error: " + data.message); 
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Hubo un problema al obtener los datos del perfil.");
    });
}

function cerrarPerfilDatos() {
    document.getElementById("perfilDatos").style.display = "none";
    document.getElementById("perfilNombre").disabled = true;
    document.getElementById("perfilDocumento").disabled = true;
    document.getElementById("perfilEmail").disabled = true;
    document.getElementById("perfilTelefono").disabled = true;
}


function habilitar(){
    document.getElementById("perfilEmail").disabled= false;
    document.getElementById("perfilTelefono").disabled= false;
    
}

function modificarPerfil(){
    const action ="modificar";
    const perfilEmail = document.getElementById("perfilEmail").value;
    const perfilTelefono = document.getElementById("perfilTelefono").value;

} 