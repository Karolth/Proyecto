window.addEventListener('load', () => { //Ejecuta la función callback cuando la ventana se carga por completo
    
    setTimeout(() => { //Simula un tiempo de carga de 1 segundos para mostrar un preloader antes de revelar el contenido principal
        
        document.querySelector('.preloader').style.display = 'none'; // Hace desaparecer el preloader una vez que ha transcurrido el tiempo.

        // Mostrar el contenedor del login
        const container = document.querySelector('container'); //Almacena una referencia al contenedor del login
        container.classList.remove('hidden'); //Permite mostrar el contenedor al eliminar una clase que oculta su visibilidad.
        container.style.display = 'flex'; // Aplica el diseño de Flexbox para estructurar los elementos hijos del contenedor.
        container.style.position = 'fixed'; // Fija el contenedor en la misma posición relativa a la ventana, incluso cuando el usuario hace scroll.
    }, 2000); // Tiempo del preloader (1 segundos)
});