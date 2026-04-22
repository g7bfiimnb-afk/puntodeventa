// Usamos un condicional para evitar el error de "ya declarado" si el script se carga dos veces
if (typeof carrito === 'undefined') {
    var carrito = []; 
} else {
    carrito = []; // Reiniciar si ya existe
}

$(document).ready(function() {
    
    // 1. EVENTO AL BUSCAR (Enter o Clic en Lupa)
    $('#buscar_producto').on('keypress', function(e) {
        if (e.which == 13) ejecutarBusqueda();
    });

    $('#btn_buscar_lupa').on('click', function() {
        ejecutarBusqueda();
    });

    function ejecutarBusqueda() {
        let valor = $('#buscar_producto').val();
        if (valor == "") return $('#buscar_producto').focus();

        $.ajax({
            url: 'ajax/VentaAjax.php',
            method: 'POST',
            data: { buscar_codigo: valor },
            success: function(respuesta) {
                try {
                    let res = JSON.parse(respuesta);
                    if(res.res == "success") {
                        agregarAlCarrito(res.data);
                        $('#buscar_producto').val("").focus(); 
                    } else {
                        alert(res.msj);
                        $('#buscar_producto').select();
                    }
                } catch (e) {
                    console.error("Error al parsear JSON:", respuesta);
                }
            }
        });
    }

    // 2. ACTUALIZACIÓN EN TIEMPO REAL (Escucha cambios en los inputs de la tabla)
    $(document).on('input', '.input-precio, .input-cantidad', function() {
        let fila = $(this).closest('tr');
        let index = fila.data('index'); // Obtenemos la posición en el arreglo
        
        let nuevoPrecio = parseFloat(fila.find('.input-precio').val()) || 0;
        let nuevaCantidad = parseFloat(fila.find('.input-cantidad').val()) || 0;

        // Actualizamos el objeto en el arreglo global 'carrito'
        carrito[index].precio_venta = nuevoPrecio;
        carrito[index].cantidad = nuevaCantidad;

        // Calculamos subtotal de la fila visualmente
        let subtotal = nuevoPrecio * nuevaCantidad;
        fila.find('.subtotal-fila').text('$ ' + subtotal.toFixed(2));
        
        actualizarTotalGeneral();
    });

    // 3. CAMBIO DE UNIDAD (Kg / Pza)
    $(document).on('change', '.select-unidad', function() {
        let fila = $(this).closest('tr');
        let unidad = $(this).val();
        let inputCant = fila.find('.input-cantidad');

        if(unidad == "kg") {
            inputCant.attr('step', '0.001').val(0.000); // Para gramos/kilos
        } else {
            inputCant.attr('step', '1').val(1); // Para piezas enteras
        }
        // Disparamos el evento input para recalcular el subtotal
        inputCant.trigger('input');
    });

    // 4. VACIAR CARRITO
    $('#vaciar_carrito').on('click', function() {
        if (carrito.length === 0) {
            alert("El carrito ya está vacío");
            return;
        }
        if (confirm("¿Deseas vaciar todo el carrito?")) {
            carrito = [];
            renderizarTabla();
        }
    });

    // 5. ABRIR MODAL AL COBRAR
    $('#btn_finalizar_venta').on('click', function() {
        if (carrito.length === 0) {
            alert("El carrito está vacío");
            return;
        }

        let total = parseFloat($('#total_venta_input').val()) || 0;
        
        // Llenar el modal con el total
        $('#m_total_pagar').text('$ ' + total.toFixed(2));
        $('#m_efectivo_recibido').val("");
        $('#m_cambio').text('$ 0.00').removeClass('text-danger');
        
        $('#modalCobrar').modal('show');

        // Enfocar automáticamente el input de efectivo después de abrir
        $('#modalCobrar').on('shown.bs.modal', function () {
            $('#m_efectivo_recibido').focus();
        });
    });

    // 6. CALCULAR EL CAMBIO EN TIEMPO REAL
    $(document).on('input', '#m_efectivo_recibido', function() {
        let total = parseFloat($('#total_venta_input').val()) || 0;
        let efectivo = parseFloat($(this).val()) || 0;
        let cambio = efectivo - total;

        if (cambio < 0) {
            $('#m_cambio').text('$ 0.00').addClass('text-danger');
        } else {
            $('#m_cambio').text('$ ' + cambio.toFixed(2)).removeClass('text-danger');
        }
    });

    // 7. CONFIRMAR LA VENTA FINAL (Botón dentro del Modal)
    $(document).on('click', '#m_btn_confirmar_venta', function() {
        let total = parseFloat($('#total_venta_input').val()) || 0;
        let efectivo = parseFloat($('#m_efectivo_recibido').val()) || 0;

        if (efectivo < total) {
            alert("El dinero recibido es menor al total de la venta.");
            return;
        }

        // Enviar los datos al servidor
        $.ajax({
            url: 'ajax/VentaAjax.php',
            method: 'POST',
            data: {
                productos_venta: JSON.stringify(carrito),
                total_venta: total
            },
            success: function(r) {
                try {
                    let res = JSON.parse(r);
                    if (res.res == "success") {
                        alert(res.msj);
                        $('#modalCobrar').modal('hide');
                        carrito = [];
                        renderizarTabla();
                        location.reload();
                    } else {
                        alert("Error: " + res.msj);
                    }
                } catch (e) {
                    console.error("Error al procesar cobro:", r);
                    alert("Respuesta inválida del servidor.");
                }
            },
            error: function() {
                alert("Error de conexión con el servidor.");
            }
        });
    });
});

// --- FUNCIONES GLOBALES ---

function agregarAlCarrito(producto) {
    // Buscamos si ya está en el carrito para sumar cantidad
    let existe = carrito.find(item => item.id === producto.id);

    if(existe) {
        existe.cantidad++;
    } else {
        producto.cantidad = 1;
        carrito.push(producto);
    }
    renderizarTabla();
}

function renderizarTabla() {
    let html = '';
    carrito.forEach((item, index) => {
        let precio = parseFloat(item.precio_venta);
        let cantidad = item.cantidad || 1;
        let subtotal = precio * cantidad;
        
        html += `
            <tr data-index="${index}">
                <td>${item.nombre}</td>
                <td>
                    <input type="number" class="form-control form-control-sm input-precio" value="${precio.toFixed(2)}" step="0.01" min="0">
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control input-cantidad" value="${cantidad.toFixed(2)}" step="0.01" min="0">
                        <div class="input-group-append">
                            <select class="form-control form-control-sm select-unidad border-left-0">
                                <option value="pza">Pza</option>
                                <option value="kg">Kg</option>
                            </select>
                        </div>
                    </div>
                </td>
                <td class="subtotal-fila font-weight-bold text-right">$ ${subtotal.toFixed(2)}</td>
                <td class="text-center">
                    <button onclick="eliminarDelCarrito(${index})" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    $('#lista_venta').html(html);
    actualizarTotalGeneral();
    
    // Mostrar mensaje si carrito está vacío
    if (carrito.length === 0) {
        $('#lista_venta').html('<tr><td colspan="5" class="text-center text-muted py-3">El carrito está vacío</td></tr>');
    }
}

function actualizarTotalGeneral() {
    let total = 0;
    carrito.forEach(item => {
        total += (item.precio_venta * item.cantidad);
    });
    // Actualizar el total visible en grande
    $('#total_venta_display').text('$ ' + total.toFixed(2));
    // Guardar el valor en input hidden para usar después
    $('#total_venta_input').val(total.toFixed(2));
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    renderizarTabla();
}