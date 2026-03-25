$(document).ready(function() {

    // 1. INICIALIZACIÓN DE DATATABLES
    // Solo una vez, con todas las funciones de ordenamiento activadas
    $('#tablaInventario').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        "order": [[1, "asc"]], // Orden inicial por Nombre (A-Z)
        "columnDefs": [
            { "targets": 4, "orderable": false } // La columna 'Acciones' no tiene flechas de orden
        ],
        "pageLength": 10,
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    });

    // 2. GUARDAR NUEVO PRODUCTO (DESDE LA MODAL)
    $('#form_producto_ajax').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'ajax/ProductoAjax.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(r) {
                try {
                    let res = JSON.parse(r);
                    if(res.res == "success") {
                        $('#modalNuevoProducto').modal('hide');
                        alert(res.msj);
                        location.reload(); 
                    } else {
                        alert(res.msj);
                    }
                } catch (error) {
                    console.error("Error al procesar respuesta:", r);
                }
            }
        });
    });

    // 3. ELIMINAR PRODUCTO
    // Usamos delegación de eventos por si la tabla cambia
    $(document).on('click', '.btn-eliminar', function() {
        let id = $(this).attr('data-id');

        if (confirm("¿Estás seguro de eliminar este producto?")) {
            $.ajax({
                url: 'ajax/ProductoAjax.php',
                method: 'POST',
                data: { id_eliminar: id },
                success: function(respuesta) {
                    try {
                        let res = JSON.parse(respuesta);
                        if (res.res == "success") {
                            alert(res.msj);
                            location.reload(); 
                        } else {
                            alert(res.msj);
                        }
                    } catch (error) {
                        console.error("Error al eliminar:", respuesta);
                    }
                }
            });
        }
    });

}); // Fin de $(document).ready