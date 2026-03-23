let carrito = [];

function agregarAlCarrito(producto) {
    // Verificar si el producto ya está en el carrito
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
    let total = 0;

    carrito.forEach((item, index) => {
        let subtotal = item.precio_venta * item.cantidad;
        total += subtotal;
        html += `
            <tr>
                <td>${item.nombre}</td>
                <td>$${item.precio_venta}</td>
                <td>${item.cantidad}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td><button onclick="eliminarDelCarrito(${index})" class="btn btn-danger btn-sm">x</button></td>
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