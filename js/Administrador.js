
let btn = document.querySelector("#btn");
let sidebar = document.querySelector(".sidebar");

btn.onclick = function() {
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

document.addEventListener('DOMContentLoaded', function() {
  let btn = document.querySelector("#btn");
  let sidebar = document.querySelector(".sidebar");

  if (btn && sidebar) {
      btn.onclick = function() {
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
        })
        .catch(error => {
            mensajeBusqueda.innerHTML = "Error en la búsqueda.";
            console.error("Error:", error);
        });
}