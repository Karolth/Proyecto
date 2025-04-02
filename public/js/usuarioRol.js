$(document).ready(function () {
    function cargarRoles() {
        $.ajax({
            url: '../controllers/cargarRoles.php', // Archivo que obtiene los roles desde la BD
            type: 'POST',
            dataType: 'json', // Especificamos que esperamos JSON
            success: function (roles) {
                let select = $('#rol');
                select.empty();
                select.append('<option value="">Seleccionar</option>');

                roles.forEach(function (rol) {
                    select.append(`<option value="${rol.IdRol}">${rol.Rol}</option>`);
                });
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar roles:", error);
            }
        });
    }

    cargarRoles(); // Cargar los roles al iniciar la página

    $('#usuarioForm').submit(function (e) {
        e.preventDefault();

        let usuarioData = {
            documento: $('#Documento').val(),
            nombre: $('#Nombre').val(),
            email: $('#Email').val(),
            celular: $('#Celular').val(),
            rol: $('#rol').val()
        };

        console.log("Rol seleccionado:", usuarioData.rol); // Verificar en la consola

        $.ajax({
            url: '../controllers/usuarioRol.php', // Archivo PHP para guardar el usuario
            type: 'POST',
            data: usuarioData,
            dataType: 'json',  // Ahora esperamos JSON en la respuesta
            success: function (response) {
                if (response.success) {
                    alert(response.success); // Mensaje claro de éxito
                    $('#usuarioForm')[0].reset();
                } else {
                    alert(response.error); // Mensaje claro de error
                }
            },
            error: function (xhr, status, error) {
                console.error("Error al registrar usuario:", error);
                alert("Ocurrió un error al registrar el usuario.");
            }
        });
    });
});
