document.addEventListener('DOMContentLoaded', function () {
    fetch('../controllers/cargarProgramas.php') 
        .then(response => response.json())
        .then(result => {
            const select = document.getElementById('programaFormacion');
            if (result.success && result.data.length > 0) {
                result.data.forEach(programa => {
                    const option = document.createElement('option');
                    option.value = programa.Nombre;
                    option.textContent = programa.Nombre;
                    select.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.textContent = 'No se encontraron programas de formación';
                option.disabled = true;
                select.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Error al cargar los programas:', error);
        });
});


document.addEventListener('DOMContentLoaded', function () {
    const btnSubmit = document.querySelector('.btn-submit');

    btnSubmit.addEventListener('click', function (event) {
        event.preventDefault();

        const form = document.getElementById('formFichaCompleta');
        const nombrePrograma = document.getElementById('programaFormacion').value;
        const jornada = document.getElementById('jornada').value;
        const tipoPrograma = document.getElementById('tipoPrograma').value;
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;
        const numeroFicha = document.getElementById('numeroFicha').value;
        const archivoExcel = document.getElementById('archivoExcel').files[0];

        if (!nombrePrograma || !jornada || !tipoPrograma || !fechaInicio || !fechaFin || !numeroFicha || !archivoExcel) {
            alert('Por favor, complete todos los campos y seleccione un archivo.');
            return;
        }

        // ✅ FormData para enviar archivos + datos
        const formData = new FormData();
        formData.append('programaFormacion', nombrePrograma);
        formData.append('jornada', jornada);
        formData.append('tipoPrograma', tipoPrograma);
        formData.append('fechaInicio', fechaInicio);
        formData.append('fechaFin', fechaFin);
        formData.append('numeroFicha', numeroFicha);
        formData.append('archivoExcel', archivoExcel); // archivo

        fetch('../controllers/guardar_ficha.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Ficha creada exitosamente con aprendices.');
                    document.getElementById('mensajeExito').style.display = 'block';
                    form.reset();
                } else {
                    alert('Error al crear la ficha: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un problema al guardar la ficha.');
            });
    });

    // Botón cancelar
    document.getElementById('btnCancelar').addEventListener('click', function () {
        document.getElementById('formFichaCompleta').reset();
    });
});
