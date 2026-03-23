// Usamos un condicional para evitar el error de "ya declarado" si el script se carga dos veces
if (typeof carrito === 'undefined') {
    var carrito = []; 
} else {
    carrito = []; // Si ya existe, lo reiniciamos
}

$(document).ready(function() {
    
    // 1. Evento cuando presionan ENTER en el buscador
    $('#buscar_producto').on('keypress', function(e) {
        if (e.which == 13) { 
            ejecutarBusqueda();
        }
    });

    // 2. Evento cuando hacen CLIC en la LUPA
    $('#btn_buscar_lupa').on('click', function() {
        ejecutarBusqueda();
    });

    // 3. Función principal de búsqueda
    function ejecutarBusqueda() {
        let valor = $('#buscar_producto').val();
        if (valor == "") {
            $('#buscar_producto').focus();
            return;
        }

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
                    alert("Error en la respuesta del servidor. Revisa la consola.");
                }
            }
        });
    }

    // 4. Botón FINALIZAR VENTA (COBRAR)
    $('#btn_finalizar_venta').on('click', function() {
        if (carrito.length === 0) {
            alert("El carrito está vacío");
            return;
        }

        if(!confirm("¿Desea procesar esta venta de $" + $('#total_venta').text() + "?")) return;

        $.ajax({
            url: 'ajax/VentaAjax.php',
            method: 'POST',
            data: {
                productos_venta: carrito,
                total_venta: $('#total_venta').text()
            },
            success: function(r) {
                try {
                    let res = JSON.parse(r);
                    if (res.res == "success") {
                        alert(res.msj);
                        carrito = []; 
                        renderizarTabla();
                        location.reload(); 
                    } else {
                        alert(res.msj);
                    }
                } catch (e) {
                    console.error("Error al procesar cobro:", r);
                }
            }
        });
    });
});

// --- FUNCIONES GLOBALES ---

function agregarAlCarrito(producto) {
    let existe = carrito.find(item => item.id === producto.id);

    if(existe) {
        if(parseInt(existe.cantidad) >= parseInt(producto.stock)) {
            alert("No hay más stock disponible (Máximo: " + producto.stock + ")");
            return;
        }
        existe.cantidad++;
    } else {
        producto.cantidad = 1;
        carrito.push(producto);
    }
    renderizarTabla();
}

function renderizarTabla() {
    let html = '';
    let total = 0;

    carrito.forEach((item, index) => {
        let precio = parseFloat(item.precio_venta);
        let subtotal = precio * item.cantidad;
        total += subtotal;
        
        html += `
            <tr>
                <td>${item.nombre}</td>
                <td>$${precio.toFixed(2)}</td>
                <td>
                    <span class="badge badge-light border px-3 py-2">${item.cantidad}</span>
                </td>
                <td>$${subtotal.toFixed(2)}</td>
                <td>
                    <button onclick="eliminarDelCarrito(${index})" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    $('#lista_venta').html(html);
    $('#total_venta').text(total.toFixed(2));
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    renderizarTabla();
}