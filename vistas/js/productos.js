$(document).ready(function() {
    $('#form_producto_ajax').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax/ProductoAjax.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(r) {
                let res = JSON.parse(r);
                if(res.res == "success") {
                    alert(res.msj);
                    location.reload(); // Recarga para ver el nuevo producto en la tabla
                } else {
                    alert(res.msj);
                }
            }
        });
    });
});

// Añade esto al final del archivo productos.js
$(document).on('click', '.btn-eliminar', function() {
    let id = $(this).attr('data-id');

    if (confirm("¿Estás seguro de eliminar este producto?")) {
        $.ajax({
            url: 'ajax/ProductoAjax.php',
            method: 'POST',
            data: { id_eliminar: id },
            success: function(respuesta) {
                let res = JSON.parse(respuesta);
                if (res.res == "success") {
                    alert(res.msj);
                    location.reload(); // Recargamos para actualizar la tabla
                } else {
                    alert(res.msj);
                }
            }
        });
    }
});