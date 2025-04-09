 // Abrir modales
 document.getElementById('subirImagen').addEventListener('click', function() {
    document.getElementById('modalSubirImagen').style.display = 'block';
});

document.getElementById('crearFicha').addEventListener('click', function() {
    document.getElementById('modalCrearFicha').style.display = 'block';
});

document.getElementById('historial').addEventListener('click', function() {
    document.getElementById('modalHistorial').style.display = 'block';
});

// Cerrar modales con el botón X
const closeButtons = document.querySelectorAll('.close');
closeButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        const modalId = this.getAttribute('data-modal');
        document.getElementById(modalId).style.display = 'none';
    });
});

// Cerrar modal al hacer clic fuera de él
window.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

// Cerrar session
    document.getElementById("logoutBtn").addEventListener("click", function () {
        window.location.href = "../views/loginEasyCodeIS.html";
    });

