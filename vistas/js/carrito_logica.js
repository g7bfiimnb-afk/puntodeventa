let carrito = JSON.parse(localStorage.getItem('carrito_tienda')) || [];
actualizarNumero();
actualizarListaCarrito(); // muestra la lista si ya había productos en localStorage

function agregarAlCarrito(id, nombre, precio, stock) {
    id = parseInt(id, 10);
    precio = parseFloat(precio);
    stock = parseInt(stock, 10);

    let item = carrito.find(p => parseInt(p.id, 10) === id);

    if (stock <= 0 || isNaN(stock)) {
        return alert("No hay stock disponible para este producto.");
    }

    if (item) {
        if (item.cantidad + 1 > stock) {
            return alert("No puede agregar más unidades. Stock máximo alcanzado.");
        }
        item.cantidad++;
    } else {
        carrito.push({ id: id, nombre: nombre, precio: precio, cantidad: 1 });
    }

    localStorage.setItem('carrito_tienda', JSON.stringify(carrito));
    actualizarNumero();
    actualizarBadgeProducto(id);
    actualizarListaCarrito(); // Actualiza tabla de carrito al instante
    alert("Añadido: " + nombre);
}

function actualizarBadgeProducto(productoId) {
    productoId = parseInt(productoId, 10);
    let carrito = JSON.parse(localStorage.getItem('carrito_tienda')) || [];
    let item = carrito.find(p => parseInt(p.id, 10) === productoId);
    if(item) {
        $("#count-prod-" + productoId).text(item.cantidad);
    }
}

function resetBadgesDeProductos() {
    $(".btn-agregar-carrito .badge").text('0');
}

function actualizarNumero() {
    const badge = document.getElementById('num_cart');
    if(badge) {
        let total = carrito.reduce((acc, p) => acc + p.cantidad, 0);
        badge.innerText = total;
    }
}

function finalizarCompra() {
    if(carrito.length === 0) return alert("El carrito está vacío");

    $.ajax({
        url: 'ajax/VentaAjax.php',
        method: 'POST',
        data: {
            productos_venta: JSON.stringify(carrito),
            total_venta: carrito.reduce((acc, p) => acc + (p.precio * p.cantidad), 0)
        },
        success: function(res) {
            let respuesta = JSON.parse(res);
            if(respuesta.res === 'success') {
                alert("¡Compra exitosa!");
                carrito = [];
                localStorage.removeItem('carrito_tienda');
                location.reload();
            }
        }
    });
}