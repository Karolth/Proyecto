function mostrarPerfil() {
    document.getElementById("perfilDatos").style.display = "block";
    const action = "getPerfil";

    fetch("/Proyecto/php/verPerfil.php", {
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
            
        } else {
            alert("Error: " + data.message); // 'messsage' corregido a 'message'
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