 // Abrir modales
 document.getElementById('subirImagen').addEventListener('click', function() {
    document.getElementById('modalSubirImagen').style.display = 'block';
    document.body.style.overflow = 'hidden'; 
});

document.getElementById('crearFicha').addEventListener('click', function() {
    document.getElementById('modalCrearFicha').style.display = 'block';
    document.body.style.overflow = 'hidden';
});

document.getElementById('historial').addEventListener('click', function() {
    document.getElementById('modalHistorial').style.display = 'block';
    document.body.style.overflow = 'hidden';
});


const closeButtons = document.querySelectorAll('.close');
closeButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        const modalId = this.getAttribute('data-modal');
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto'; 
    });
});

// Cerrar modal al hacer clic fuera de él
window.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});

// Cerrar sesión
document.getElementById("logoutBtn").addEventListener("click", function() {
 
    window.location.href = "../views/loginEasyCodeIS.html";
});


document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            if (modal.style.display === 'block') {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }
});