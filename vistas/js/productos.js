$(document).ready(function() {

    // 1. BOTÓN AGREGAR (EL AZUL ARRIBA A LA DERECHA)
    window.abrirModalNuevo = function() {
        console.log('abrirModalNuevo() ejecutándose');
        // Limpiar el formulario
        if ($('#form_producto_ajax').length) {
            $('#form_producto_ajax')[0].reset();
        }
        // Establecer valores
        $('#id_producto').val('');
        $('#modo_prod').val('nuevo');
        $('#tituloModal').text('Registrar Nuevo Producto');
        
        // Mostrar modal
        $('#modalProducto').modal('show');
        console.log('Modal abierto');
    };

    // 2. BOTÓN EDITAR (EL AMARILLO EN LA TABLA)
    $(document).on('click', '.btn-editar-prod', function() {
        let btn = $(this);
        $('#id_producto').val(btn.data('id'));
        $('#codigo_barras').val(btn.data('codigo'));
        $('#nombre').val(btn.data('nombre'));
        $('#precio_compra').val(btn.data('pcompra'));
        $('#precio_venta').val(btn.data('pventa'));
        $('#stock').val(btn.data('stock'));
        $('#categoria').val(btn.data('cat'));
        $('#modo_prod').val('editar');
        $('#tituloModal').text('Editar Producto');
        $('#modalProducto').modal('show');
    });

    // 3. BOTÓN ELIMINAR (EL ROJO)
    $(document).on('click', '.btn-eliminar-prod', function() {
        let id = $(this).data('id');
        if (confirm("¿Seguro que deseas eliminar este producto?")) {
            $.ajax({
                url: 'ajax/ProductoAjax.php',
                method: 'POST',
                data: { id_eliminar_prod: id },
                success: function(r) {
                    try {
                        let res = JSON.parse(r);
                        if (res.res == "success") location.reload();
                    } catch(e) { alert("Error al eliminar"); }
                }
            });
        }
    });

    // 4. GUARDAR / EDITAR (BOTÓN VERDE)
    $('#form_producto_ajax').on('submit', function(e) {
        e.preventDefault();
        console.log('Enviando formulario de producto...');
        $.ajax({
            url: 'ajax/ProductoAjax.php',
            method: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(respuesta) {
                console.log('Respuesta del servidor:', respuesta);
                try {
                    let res = JSON.parse(respuesta);
                    if (res.res == "success") {
                        alert(res.msj || "Producto guardado correctamente");
                        location.reload();
                    } else {
                        alert("Error: " + (res.msj || "No se pudo guardar en la base de datos."));
                    }
                } catch (e) {
                    console.error("Respuesta cruda del servidor:", respuesta);
                    alert("Error técnico. Revisa la consola (F12).");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error AJAX:", error);
                console.error("Status:", status);
                console.error("Response:", xhr.responseText);
                alert("Error al conectar con el servidor: " + error);
            }
        });
    });
});