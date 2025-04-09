document.addEventListener("DOMContentLoaded", function() {
    const inputImagenes = document.getElementById("imagenes");
    const previewContainer = document.getElementById("previewContainer");
    const fileInfo = document.getElementById("fileInfo");
    const dropZone = document.getElementById("dropZone");
    
    // Abrir el selector de archivos al hacer clic en la zona de arrastrar
    dropZone.addEventListener("click", function() {
        inputImagenes.click();
    });
    
    // Manejo de eventos de arrastrar y soltar
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropZone.classList.add('dragover');
    }
    
    function unhighlight() {
        dropZone.classList.remove('dragover');
    }
    
    // Manejar archivos soltados
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        inputImagenes.files = files;
        handleFiles(files);
    }
    
    // Manejar cambios en el input de archivos
    inputImagenes.addEventListener("change", function(event) {
        handleFiles(event.target.files);
    });
    
    function handleFiles(files) {
        previewContainer.innerHTML = ""; // Limpiar el contenedor de vistas previas
        
        if (files.length === 0) {
            fileInfo.textContent = "No hay archivos seleccionados";
            return;
        }
        
        let validFiles = 0;
        let totalSize = 0;
        const tiposValidos = ["image/jpeg", "image/png", "image/gif"];
        const tamañoMaximo = 2 * 1024 * 1024; // 2MB
        
        for (const archivo of files) {
            if (!tiposValidos.includes(archivo.type)) {
                alert("Solo se permiten imágenes JPG, PNG o GIF.");
                continue;
            }
            
            if (archivo.size > tamañoMaximo) {
                alert(`El archivo ${archivo.name} es demasiado grande (Máximo 2MB).`);
                continue;
            }
            
            validFiles++;
            totalSize += archivo.size;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = document.createElement("div");
                imgContainer.style.position = "relative";
                imgContainer.style.margin = "5px";
                
                const img = document.createElement("img");
                img.src = e.target.result;
                img.style.maxWidth = "150px";
                img.style.height = "100px";
                imgContainer.appendChild(img);
                
                const nameLabel = document.createElement("div");
                nameLabel.textContent = archivo.name.length > 15 ? archivo.name.substring(0, 12) + "..." : archivo.name;
                nameLabel.style.fontSize = "12px";
                nameLabel.style.textAlign = "center";
                nameLabel.style.marginTop = "5px";
                imgContainer.appendChild(nameLabel);
                
                previewContainer.appendChild(imgContainer);
            };
            reader.readAsDataURL(archivo);
        }
        
        // Actualizar información de archivos
        const totalSizeMB = (totalSize / (1024 * 1024)).toFixed(2);
        fileInfo.textContent = `${validFiles} archivo(s) seleccionado(s) - Total: ${totalSizeMB}MB`;
    }
});