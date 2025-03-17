$(document).ready(function () {
    function cargarRoles() {
        $.ajax({
            url: '../php/usuarioRol.php',
            type: 'POST',
            success: function (data) {
                let roles = JSON.parse(data);
                let select = $('#rol');
                select.empty();
                select.append('<option value="">Seleccionar</option>');
                select.append('<option value="1">Instructor</option>');
                select.append('<option value="2">Visitante</option>');
                roles.forEach(function (rol) {
                    select.append(`<option value="${rol.IdRol}">${rol.Rol}</option>`);
                });
            }
        });
    }
    cargarRoles();

    $('#usuarioForm').submit(function (e) {
        e.preventDefault();
        
        let usuarioData = {
            documento: $('#Documento').val(),
            nombre: $('#Nombre').val(),
            email: $('#Email').val(),
            celular: $('#Celular').val(),
            rol: $('#rol').val()
        };

        $.ajax({
            url: '../php/usuarioRol.php',
            type: 'POST',
            data: usuarioData,
            success: function (response) {
                alert(response);
                $('#usuarioForm')[0].reset();
            }
        });
    });
});
