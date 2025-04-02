document.addEventListener('DOMContentLoaded', function() {
    const btnSubmit = document.querySelector('.btn-submit');
    
    btnSubmit.addEventListener('click', function(event) {
        event.preventDefault();
        
        const nombrePrograma = document.getElementById('nombreSalon').value;
        const jornada = document.getElementById('jornada').value;
        const tipoPrograma = document.getElementById('tipoPrograma').value;
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;
        const numeroFicha = document.getElementById('numeroFicha').value;

      
        if (!nombrePrograma || !jornada || !tipoPrograma || !fechaInicio || !fechaFin || !numeroFicha) {
            alert('Por favor, complete todos los campos');
            return;
        }

      
        const fichaData = {
            nombrePrograma: nombrePrograma,
            jornada: jornada,
            tipoPrograma: tipoPrograma,
            fechaInicio: fechaInicio,
            fechaFin: fechaFin,
            numeroFicha: numeroFicha
        };

       
        fetch('../controllers/guardar_ficha.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(fichaData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
              
                document.getElementById('mensajeExito').style.display = 'block';
                
                
                document.getElementById('formCrearSalon').reset();
            } else {
                
                alert('Error al crear la ficha: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema al guardar la ficha');
        });
    });

    
    document.getElementById('btnCancelar').addEventListener('click', function() {
        
        document.getElementById('formCrearSalon').reset();
    });
});