$(document).ready(function() {
    console.log("JS Productos cargado");

    // 1. Datatables (Se mantiene igual)
    $('#tablaProductos').DataTable({
        "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        "order": [[1, "asc"]],
        "columnDefs": [ { "targets": 4, "orderable": false } ]
    });

    // 2. Lógica para NUEVO PRODUCTO (Limpiar modal)
    $('#btnNuevoProducto').click(function(){
        $('#tituloModal').text("Nuevo Producto");
        $('#form_producto_ajax')[0].reset(); // Limpiar inputs text
        $('#id_producto').val(""); // Asegurar que ID esté vacío
        $('#previsualizarFoto').attr('src', './vistas/img/productos/default.png'); // Reset foto
        $('.custom-file-label').text("Seleccionar archivo..."); // Reset label
    });

    // 3. Lógica para EDITAR (Cargar datos)
    $(document).on('click', '.btn-editar-prod', function(){
        let id_p = $(this).attr('data-id');
        let foto_p = $(this).attr('data-foto');

        $('#tituloModal').text("Editar Producto");
        $('#id_producto').val(id_p);

        // Previsualizar la foto que ya tiene
        let rutaFoto = (foto_p && foto_p != 'default.png') ? './vistas/img/productos/'+foto_p : './vistas/img/productos/default.png';
        $('#previsualizarFoto').attr('src', rutaFoto);

        // AJAX para traer los datos técnicos del producto
        $.ajax({
            url: 'ajax/ProductoAjax.php',
            method: 'POST',
            data: { id_traer: id_p },
            dataType: 'json',
            success: function(res){
                // Llenar los campos del modal
                $('#codigo_prod').val(res.codigo);
                $('#nombre_prod').val(res.nombre);
                $('#p_compra_prod').val(res.precio_compra);
                $('#p_venta_prod').val(res.precio_venta);
                $('#stock_prod').val(res.stock);
                
                $('#modalProducto').modal('show');
            }
        });
    });

    // 4. Previsualizar Imagen al seleccionarla
    $("#foto_prod").change(function(){
        // Cambiar el texto del label
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").text(fileName);

        // Previsualizar
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#previsualizarFoto').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // 5. Enviar Formulario (Nuevo o Editar con Foto)
    $('#form_producto_ajax').on('submit', function(e) {
        e.preventDefault();
        
        // IMPORTANTE: Para subir archivos NO SE USA .serialize()
        // Se usa FormData
        let datos = new FormData(this);

        $.ajax({
            url: 'ajax/ProductoAjax.php',
            method: 'POST',
            data: datos,
            cache: false,
            contentType: false, // OBLIGATORIO para FormData
            processData: false, // OBLIGATORIO para FormData
            success: function(respuesta) {
                console.log(respuesta);
                try {
                    let res = JSON.parse(respuesta);
                    if(res.res == "success") {
                        $('#modalProducto').modal('hide');
                        alert(res.msj);
                        location.reload(); 
                    } else {
                        alert("Error: " + res.msj);
                    }
                } catch (error) {
                    console.error("Error servidor:", respuesta);
                    alert("Error crítico del servidor. Revisa consola.");
                }
            }
        });
    });
});// Fin de $(document).ready