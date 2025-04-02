cargarDatosVehiculos();// Función para cargar los datos de vehículos
function cargarDatosVehiculos() {
    // Obtener referencia al tbody de la tabla
    const tbody = document.getElementById('datosVehiculos');
    
    // Realizar petición AJAX para obtener los datos
    fetch('/EasyCode/PHP/MoviVehiculo.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            // Limpiar la tabla antes de agregar nuevos datos
            tbody.innerHTML = '';
            
            // Verificar si hay datos
            if (data.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" style="text-align: center;">No hay datos disponibles</td>';
                tbody.appendChild(row);
                return;
            }
            
            // Recorrer los datos y agregarlos a la tabla
            data.forEach(vehiculo => {
                const row = document.createElement('tr');
                
                // Crear las celdas con los datos
                row.innerHTML = `
                    <td><span class="etiqueta id-movimiento-vehiculo">${vehiculo.IdMovimientoVehiculo}</span></td>
                    <td><span class="etiqueta estado">${vehiculo.Estado}</span></td>
                    <td><span class="etiqueta placa">${vehiculo.Placa}</span></td>
                    <td><span class="etiqueta materia">${vehiculo.IdTipoVehiculo}</span></td>
                `;
                
                // Agregar la fila a la tabla
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
            
            // Mostrar mensaje de error en la tabla
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align: center; color: red;">
                        Error al cargar los datos: ${error.message}
                    </td>
                </tr>
            `;
        });
}
