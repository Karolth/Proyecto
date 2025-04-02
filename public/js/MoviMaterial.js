document.addEventListener('DOMContentLoaded', function() {
    // Fetch data when the page loads
    cargarMovimientosMaterial();
});

function cargarMovimientosMaterial() {
    fetch('../PHP/MoviMaterial.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            mostrarMovimientosMaterial(data);
        })
        .catch(error => {
            console.error('Error al cargar los movimientos:', error);
        });
}

function mostrarMovimientosMaterial(movimientos) {
    const tbody = document.querySelector('#Material table tbody');
    tbody.innerHTML = ''; // Clear existing rows
    
    if (movimientos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5">No hay movimientos de materiales disponibles</td></tr>';
        return;
    }
    
    movimientos.forEach(movimiento => {
        const row = document.createElement('tr');
        
        // Determine the appropriate class for the status
        let estadoClass = 'ingresado';
        if (movimiento.Estado === 'Completado') {
            estadoClass = 'completado';
        } else if (movimiento.Estado === 'salida') {
            estadoClass = 'salida';
        }
        
        row.innerHTML = `
            <td><span class="etiqueta id-movimiento-material">${movimiento.IdMovimientoMaterial}</span></td>
            <td><span class="etiqueta ${estadoClass}">${movimiento.Estado}</span></td>
            <td><span class="etiqueta referencia">${movimiento.Referencia}</span></td>
            <td><span class="etiqueta marca">${movimiento.Marca}</span></td>
            <td><span class="etiqueta materia">${movimiento.IdTipoMaterial}</span></td>
        `;
        
        tbody.appendChild(row);
    });
}