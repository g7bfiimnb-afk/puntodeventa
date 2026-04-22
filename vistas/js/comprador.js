// Carrito global de comprador
if (typeof carritoComprador === 'undefined') {
    var carritoComprador = [];
} else {
    carritoComprador = [];
}

$(document).ready(function() {

    // ========== 1. ORDENAR Y BUSCAR CATÁLOGO ==========
    // Combinamos la lógica de búsqueda y orden para que trabajen juntas
    function cargarCatalogo() {
        let criterio = $('#ordenar_catalogo').val();
        let busqueda = $('#buscar_producto_input').val(); // Captura lo que escribiste en el input

        $.ajax({
            url: 'ajax/CatalogoAjax.php',
            method: 'POST',
            data: { 
                orden: criterio,
                buscar: busqueda 
            },
            beforeSend: function() {
                $('#contenedor_productos_catalogo').css('opacity', '0.5');
            },
            success: function(respuesta) {
                $('#contenedor_productos_catalogo').html(respuesta).css('opacity', '1');
            }
        });
    }

    // Evento al cambiar el select de orden
    $('#ordenar_catalogo').on('change', cargarCatalogo);

    // EVENTO CRÍTICO: Capturar el formulario de búsqueda para evitar redirección
    $('#form_buscar_comprador').on('submit', function(e) {
        e.preventDefault(); // EVITA QUE TE MANDE AL INICIO
        cargarCatalogo();
    });


    // ========== 2. AGREGAR AL CARRITO ==========
    $(document).on('click', '.btn-agregar-carrito', function() {
        let id_producto = $(this).attr('data-id');
        
        $.ajax({
            url: 'ajax/ProductoAjax.php',
            method: 'POST',
            data: { id_traer: id_producto },
            dataType: 'json',
            success: function(producto) {
                agregarAlCarritoComprador(producto);
            }
        });
    });

    // ========== 3. VER CARRITO ==========
    $('#btn_ver_carrito').on('click', function() {
        renderizarCarritoComprador();
        $('#modalCarritoComprador').modal('show');
    });

    // ========== 4. ELIMINAR DEL CARRITO ==========
    $(document).on('click', '.btn-eliminar-carrito-item', function() {
        let index = $(this).attr('data-index');
        carritoComprador.splice(index, 1);
        actualizarBadgeCarrito();
        renderizarCarritoComprador();
    });

    // ========== 5. ACTUALIZAR CANTIDAD ==========
    $(document).on('input', '.input-cantidad-carrito', function() {
        let index = $(this).attr('data-index');
        let nuevaCantidad = parseFloat($(this).val()) || 0;
        
        if(nuevaCantidad <= 0) {
            carritoComprador.splice(index, 1);
        } else {
            carritoComprador[index].cantidad = nuevaCantidad;
        }
        
        actualizarBadgeCarrito();
        renderizarCarritoComprador();
    });

    // ========== 6. CALCULAR CAMBIO ==========
    $(document).on('input', '#monto_pagado', function() {
        let total = calcularTotalCarrito();
        let montoPagado = parseFloat($(this).val()) || 0;
        let cambio = montoPagado - total;
        
        if(cambio < 0) {
            $('#cambio_resultado').text('$ ' + cambio.toFixed(2)).removeClass('text-primary').addClass('text-danger');
        } else {
            $('#cambio_resultado').text('$ ' + cambio.toFixed(2)).removeClass('text-danger').addClass('text-primary');
        }
    });

    // ========== 7. PROCESAR COMPRA ==========
    $('#btn_procesar_compra').on('click', function() {
        if(carritoComprador.length === 0) {
            alert("El carrito está vacío");
            return;
        }
        
        let total = calcularTotalCarrito();
        let montoPagado = parseFloat($('#monto_pagado').val()) || 0;
        
        if(montoPagado === 0) {
            alert("Debes ingresar un monto de pago");
            $('#monto_pagado').focus();
            return;
        }
        
        if(montoPagado < total) {
            alert("El monto es insuficiente. Falta: $" + (total - montoPagado).toFixed(2));
            return;
        }
        
        let cambio = montoPagado - total;
        
        // Mostrar modal de confirmación personalizado
        $('#conf_total').text('$ ' + total.toFixed(2));
        $('#conf_recibido').text('$ ' + montoPagado.toFixed(2));
        $('#conf_cambio').text('$ ' + cambio.toFixed(2));
        
        $('#modalCarritoComprador').modal('hide');
        $('#modalConfirmacionCompra').modal('show');
    });

    // ========== 8. CONFIRMAR COMPRA EN MODAL ==========
    $('#btn_confirmar_compra').on('click', function() {
        let total = parseFloat($('#conf_total').text().replace('$ ', ''));
        let montoPagado = parseFloat($('#conf_recibido').text().replace('$ ', ''));
        let cambio = parseFloat($('#conf_cambio').text().replace('$ ', ''));
        
        $.ajax({
            url: 'ajax/VentaAjax.php',
            method: 'POST',
            data: {
                productos_venta: JSON.stringify(carritoComprador),
                total_venta: total,
                monto_recibido: montoPagado,
                cambio: cambio
            },
            success: function(r) {
                try {
                    let res = JSON.parse(r);
                    if (res.res == "success") {
                        $('#modalConfirmacionCompra').modal('hide');
                        mostrarNotificacionExito('Compra procesada correctamente', cambio);
                        carritoComprador = [];
                        actualizarBadgeCarrito();
                        $('#monto_pagado').val('');
                        $('#cambio_resultado').text('$ 0.00');
                        renderizarCarritoComprador();
                    } else {
                        alert("✗ Error: " + res.msj);
                    }
                } catch (e) {
                    console.error("Error:", r);
                    alert("Respuesta inesperada del servidor.");
                }
            },
            error: function() {
                alert("Error al procesar la compra.");
            }
        });
    });
});

// ==================== FUNCIONES GLOBALES ====================

function agregarAlCarritoComprador(producto) {
    let existe = carritoComprador.find(item => item.id === producto.id);
    
    if(existe) {
        existe.cantidad++;
    } else {
        producto.cantidad = 1;
        carritoComprador.push(producto);
    }
    
    actualizarBadgeCarrito();
    mostrarNotificacionAgregado(producto.nombre);
}

function renderizarCarritoComprador() {
    if(carritoComprador.length === 0) {
        $('#contenedor_carrito').html('<p class="text-center text-muted py-5"><i class="fas fa-inbox fa-3x mb-3 d-block"></i>Tu carrito está vacío</p>');
        $('#total_carrito_resumen').text('$ 0.00');
        $('#monto_pagado').val('');
        $('#cambio_resultado').text('$ 0.00');
        return;
    }
    
    let html = `
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Producto</th>
                        <th width="120">Precio</th>
                        <th width="140">Cantidad</th>
                        <th width="120">Subtotal</th>
                        <th width="60"></th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    carritoComprador.forEach((item, index) => {
        let subtotal = item.precio_venta * item.cantidad;
        html += `
            <tr>
                <td>
                    <strong>${item.nombre}</strong><br>
                    <small class="text-muted">Cod: ${item.codigo_barras}</small>
                </td>
                <td>$ ${parseFloat(item.precio_venta).toFixed(2)}</td>
                <td>
                    <input type="number" class="form-control form-control-sm input-cantidad-carrito" 
                           value="${item.cantidad}" min="0.01" step="0.01" data-index="${index}">
                </td>
                <td class="font-weight-bold text-success">$ ${subtotal.toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm btn-eliminar-carrito-item" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    $('#contenedor_carrito').html(html);
    let total = calcularTotalCarrito();
    $('#total_carrito_resumen').text('$ ' + total.toFixed(2));
    $('#monto_pagado').val('');
    $('#cambio_resultado').text('$ 0.00');
}

function calcularTotalCarrito() {
    let total = 0;
    carritoComprador.forEach(item => {
        total += (item.precio_venta * item.cantidad);
    });
    return total;
}

// MEJORA: Cuenta el total de piezas, no solo tipos de productos
function actualizarBadgeCarrito() {
    let totalPiezas = carritoComprador.reduce((sum, item) => sum + parseFloat(item.cantidad), 0);
    $('#badge_carrito').text(totalPiezas).toggle(totalPiezas > 0);
}

function mostrarNotificacionAgregado(nombreProducto) {
    let notif = $(`
        <div class="alert alert-success alert-dismissible fade show position-fixed" 
             style="top: 80px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
            <i class="fas fa-check-circle"></i> <strong>${nombreProducto}</strong> agregado al carrito
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `);
    $('body').append(notif);
    setTimeout(function() {
        notif.fadeOut(300, function() { notif.remove(); });
    }, 3000);
}

function mostrarNotificacionExito(mensaje, cambio) {
    let notif = $(`
        <div class="alert alert-info alert-dismissible fade show position-fixed" 
             style="top: 80px; right: 20px; z-index: 9999; min-width: 350px; border-radius: 10px;" role="alert">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="font-size: 2em;">✓</div>
                <div>
                    <strong>${mensaje}</strong>
                    <br>
                    <small style="font-size: 0.9em;">Cambio a entregar: <strong>$${cambio.toFixed(2)}</strong></small>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `);
    $('body').append(notif);
    setTimeout(function() {
        notif.fadeOut(300, function() { notif.remove(); });
    }, 5000);
}