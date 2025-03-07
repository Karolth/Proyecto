//const usuarioValido ="admin";
//const passValida ="1234";
let intentosRestantes =3;

function verificarLogin(){
    let action= "login";
    let Documento = document.getElementById("Documento").value;
    let password = document.getElementById("password").value;
    let mensaje = document.getElementById("message");

    fetch("../php/usuariosIS.php",{
        method: "POST",
        headers:{
            "Content-Type": "application/json"
        },
        body: JSON.stringify({action, Documento, password})
    })
    .then(response => response.json())
    .then(data => {
            if(data.success){
                mensaje.style.color = "green";
                mensaje.textContent = "El inicio de sesion ha sido exitoso";
                setTimeout(() => {
                    window.location.href= "../html/Administrador.html"
                }, 1000); 
            }else{
                intentosRestantes--;
                mensaje.style.color="Red";
                mensaje.textContent="Error" + data.message;
            }

        if (intentosRestantes===0){
            mensaje.textContent = "Cuenta Bloqueada. Vuelve a intarlo mas tarde";
            document.getElementById("Documento").disabled = true;
            document.getElementById("password").disabled = true;
            document.getElementById("submitButton").disabled = true;
            }
    })
    .catch(error => {
        console.error("Error:", error);
        mensaje.textContent="Error al iniciar sesion"
    });
}

function mostrarPerfil() {
    
    const action="getPerfil";
    fetch("../php/usuariosIS.php", {
        method: "POST",
        headers: {
            "Content-Type": "aplication/json"
        },
        body: JSON.stringify({action})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById("perfilDocumento").value = data.Documento;
            document.getElementById("perfilTelefono").value = data.Telefono;
            document.getElementById("perfilEmail").value = data.Email;
            document.getElementById("perfilUsuario").style.display = "block";
        } else {
            alert("Error: " + data.messsage);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert ("Hubo un problema al obtener los datos del perfil.");
    });
}
function cerrarPerfilDatos(){
    document.getElementById("perfilUsuario").style.display="none";
    document.getElementById("perfilDocumento").disabled= true;
    document.getElementById("perfilTelefono").disabled= true;
    document.getElementById("perfilEmail").disabled= true;
    document.getElementById("overlay").style.display = "none";
}