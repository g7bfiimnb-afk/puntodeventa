$(document).ready(function() {
    $('#buscar_producto').on('keypress', function(e) {
        if (e.which == 13) { // Si presiona Enter
            let codigo = $(this).val();
            
            $.ajax({
                url: 'ajax/VentaAjax.php',
                method: 'POST',
                data: { action: 'buscar_producto', codigo: codigo },
                success: function(response) {
                    let data = JSON.parse(response);
                    
                    if(data.res == "success") {
                        // Creamos la fila para la tabla
                        let nuevaFila = `
                            <tr>
                                <td>${data.datos.nombre}</td>
                                <td>$${data.datos.precio_venta}</td>
                                <td><input type="number" class="form-control" value="1" min="1"></td>
                                <td>$${data.datos.precio_venta}</td>
                                <td><button class="btn btn-danger btn-sm">X</button></td>
                            </tr>`;
                        
                        $('#detalle_venta').append(nuevaFila);
                        $('#buscar_producto').val('').focus();
                    } else {
                        alert(data.msj); // "Producto no encontrado"
                    }
                }
            });
        }
    });
});