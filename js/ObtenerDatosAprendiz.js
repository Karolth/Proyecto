function buscarAprendiz() {
    let documento = document.getElementById('buscarDocumento').value;
    let mensajeBusqueda = document.getElementById('mensaje-busqueda');
    mensajeBusqueda.textContent = '';

    if (documento.trim() === '') {
        mensajeBusqueda.textContent = 'Ingrese un número de documento válido';
        return;
    }

    console.log(`Buscando documento: ${documento}`); // Verificar que el input tiene valor

    fetch(`../php/obtener_datos_aprendiz.php?documento=${documento}`)
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data); // Mostrar la respuesta en consola

            if (data.error) {
                mensajeBusqueda.textContent = data.error;
            } else {
                document.getElementById('D').textContent = `N° documento: ${data.data.Documento}`;
                document.getElementById('Nombre').textContent = `${data.data.Nombre}`;
                document.getElementById('RH').textContent = `RH: ${data.data.RH}`;
                document.getElementById('Programa').textContent = `Programa de Formación: ${data.data.NombreP}`;
            }
        })
        .catch(error => console.error('Error en fetch:', error));
}
