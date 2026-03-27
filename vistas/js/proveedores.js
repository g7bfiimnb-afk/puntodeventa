$(document).ready(function() {

    // Abrir Modal para Nuevo
    window.abrirModalNuevo = function() {
        $('#form_proveedor_ajax')[0].reset();
        $('#id_proveedor').val('');
        $('#modo_prov').val('nuevo');
        $('.modal-title').text('Registrar Nuevo Proveedor');
        $('#modalProveedor').modal('show');
    };

    // Abrir Modal para Editar (Cargar Datos)
    $(document).on('click', '.btn-editar-prov', function() {
        let btn = $(this);
        $('#id_proveedor').val(btn.data('id'));
        $('#nombre_empresa').val(btn.data('nombre'));
        $('#nombre_contacto').val(btn.data('contacto'));
        $('#telefono').val(btn.data('telefono'));
        $('#correo').val(btn.data('correo'));
        $('#direccion').val(btn.data('direccion'));
        
        $('#modo_prov').val('editar');
        $('.modal-title').text('Modificar Proveedor');
        $('#modalProveedor').modal('show');
    });

    // Guardar o Actualizar
    $('#form_proveedor_ajax').on('submit', function(e) {
        e.preventDefault();
        let datos = $(this).serialize();

        $.ajax({
            url: 'ajax/ProveedorAjax.php',
            method: 'POST',
            data: datos,
            success: function(r) {
                let res = JSON.parse(r);
                if(res.res == "success") {
                    location.reload();
                } else {
                    alert("Error en la operación");
                }
            }
        });
    });

    // Eliminar
    $(document).on('click', '.btn-eliminar-prov', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        if(confirm("¿Seguro que quieres eliminar este proveedor?")) {
            $.ajax({
                url: 'ajax/ProveedorAjax.php',
                method: 'POST',
                data: { id_eliminar_prov: id },
                success: function(r) {
                    let res = JSON.parse(r);
                    if(res.res == "success") location.reload();
                }
            });
        }
    });
});