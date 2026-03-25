$(document).ready(function() {
    // 1. Activar DataTables (Buscador y flechas)
    $('#tablaProveedores').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        "order": [[0, "asc"]],
        "columnDefs": [
            { "targets": 4, "orderable": false } // Desactivar flechas en columna Acciones
        ]
    });

    // 2. Envío del Formulario por AJAX
    $('#form_proveedor_ajax').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax/ProveedorAjax.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(respuesta) {
                try {
                    let res = JSON.parse(respuesta);
                    if(res.res == "success") {
                        $('#modalNuevoProveedor').modal('hide');
                        alert(res.msj);
                        location.reload(); 
                    } else {
                        alert(res.msj);
                    }
                } catch (error) {
                    console.error("Error en servidor:", respuesta);
                }
            }
        });
    });

    // 3. Botón Eliminar
    $(document).on('click', '.btn-eliminar-prov', function() {
        let id = $(this).attr('data-id');
        if(confirm("¿Seguro que deseas eliminar este proveedor?")) {
            $.ajax({
                url: 'ajax/ProveedorAjax.php',
                method: 'POST',
                data: { id_eliminar: id },
                success: function() {
                    location.reload();
                }
            });
        }
    });
});