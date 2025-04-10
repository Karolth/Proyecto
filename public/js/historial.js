document.addEventListener("DOMContentLoaded", function() {
    let allData = []; // Almacena todos los datos del historial

    // Carga inicial del historial y almacena los datos
    fetch("../models/ModeloHistorial.php?action=obtenerHistorial")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allData = data.data; // Almacena todos los datos
                cargarHistorial(allData); // Muestra todos los datos inicialmente
            } else {
                console.error("Error al obtener historial:", data.message);
            }
        })
        .catch(error => console.error("Error en fetch:", error));

    const searchInput = document.getElementById("searchTerm");
    const suggestionsDiv = document.getElementById("suggestions");

    // Evento input para filtrar y mostrar sugerencias
    searchInput.addEventListener("input", function() {
        const searchTerm = searchInput.value.toLowerCase();
        if (searchTerm.length > 0) {
            // Filtrar por todos los campos para mostrar sugerencias
            const filteredSuggestions = allData.filter(registro => {
                return Object.values(registro).some(value => {
                    if (value !== null && value !== undefined) {
                        return value.toString().toLowerCase().includes(searchTerm);
                    }
                    return false;
                });
            });
            showSuggestions(filteredSuggestions);
        } else {
            suggestionsDiv.style.display = "none"; // Oculta las sugerencias si el campo de búsqueda está vacío
        }
    });

// Función para realizar la búsqueda y cargar los resultados
function realizarBusqueda(searchTerm) {
    const filteredData = allData.filter(registro => 
        Object.values(registro).some(value => 
            value !== null && value !== undefined && 
            value.toString().toLowerCase().includes(searchTerm.toLowerCase())
        )
    );
    cargarHistorial(filteredData);
}

// Modificar el evento click de las sugerencias
function showSuggestions(suggestions) {
    suggestionsDiv.innerHTML = "";
    if (suggestions.length > 0) {
        suggestionsDiv.style.display = "block";
        suggestions.forEach(suggestion => {
            const suggestionItem = document.createElement("div");
            suggestionItem.classList.add("suggestion");

            // Resaltar el término de búsqueda dentro de la sugerencia
            const suggestionText = Object.values(suggestion).join(" - ");
            const highlightedText = suggestionText.replace(new RegExp(searchInput.value, 'gi'), match => `<span class="highlight">${match}</span>`);

            suggestionItem.innerHTML = highlightedText; // Usar innerHTML para mostrar el texto resaltado

            suggestionItem.addEventListener("click", function() {
                searchInput.value = suggestionText; // Llena el campo de búsqueda con el texto completo
                suggestionsDiv.style.display = "none"; // Oculta las sugerencias
                realizarBusqueda(searchInput.value); // Realiza la búsqueda automáticamente
            });
            suggestionsDiv.appendChild(suggestionItem);
        });
    } else {
        suggestionsDiv.style.display = "none";
    }
}

// Modificar el evento submit del formulario para usar la nueva función
document.getElementById("searchForm").addEventListener("submit", function(event) {
    event.preventDefault();
    realizarBusqueda(searchInput.value); // Realiza la búsqueda con el término ingresado
    suggestionsDiv.style.display = "none";
});

    // Función para cargar y mostrar el historial en la tabla
    function cargarHistorial(data) {
        const tabla = document.querySelector("#tablaHistorial");
        tabla.innerHTML = "";
        data.forEach(registro => {
            let fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${registro.NombreAprendiz}</td>
                <td>${registro.Documento}</td>
                <td>${registro.NombreMaterial}</td>
                <td>${registro.Referencia}</td>
                <td>${registro.Placa}</td>
                <td>${registro.TipoVehiculo}</td>
                <td>${registro.FechaHora}</td>
                <td>${registro.movimiento}</td>
            `;
            tabla.appendChild(fila);
        });
    }
});