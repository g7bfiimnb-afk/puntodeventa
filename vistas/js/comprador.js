$(document).ready(function(){
    // Carga inicial de productos
    cargar_productos("General", 1);

    // Evita que el buscador recargue la página o te saque del sistema
    $("#form_buscar_comprador").on("submit", function(e){
        e.preventDefault(); 
        let valor = $("#buscar_producto").val();
        
        $.ajax({
            url: "ajax/ProductoAjax.php",
            method: "POST",
            data: { busqueda_tienda: valor },
            success: function(data) {
                $("#contenedor_productos_tienda").html(data);
                $("#titulo_seccion").text("Resultados para: " + valor);
            }
        });
    });

    // Agregar producto al carrito
    $(document).on("click", ".btn-agregar-carrito", function(){
        let id = $(this).data("id");
        let nombre = $(this).data("nombre");
        let precio = $(this).data("precio");
        let stock = parseInt($(this).data("stock"), 10);
        console.log('[Tienda] Add click', {id, nombre, precio, stock});
        agregarAlCarrito(id, nombre, parseFloat(precio), stock);
    });

    // Abrir el carrito
    $(document).on("click", "#btn_ver_carrito, .fa-shopping-cart", function(e){
        e.preventDefault();
        actualizarListaCarrito();
        $("#modalCarrito").modal("show");
    });

    // Filtrar por categoría
    $(document).on("click", ".btn-categoria", function(){
        let categoria = $(this).data("cat");
        console.log('[Tienda] Categoria click', categoria);
        $(".btn-categoria").removeClass("active");
        $(this).addClass("active");
        cargar_productos(categoria, 1);
    });

    // Ofertas top stock
    $("#btn_ofertas").on("click", function(){
        $(".btn-categoria").removeClass("active");
        cargar_productos('ofertas', 1);
    });

    // Cada vez que la ficha de productos se recarga, ajustar los contadores ya guardados
    $(document).ajaxComplete(function() {
        var carrito = JSON.parse(localStorage.getItem('carrito_tienda')) || [];
        carrito.forEach(function(item) {
            $("#count-prod-" + item.id).text(item.cantidad);
        });
    });

    // Confirmar compra
    $("#btn_confirmar_compra").on("click", function(){
        finalizarCompra();
    });

    // Cambiar método de pago
    $(document).on("change", "#metodo_pago", function(){
        let metodo = $(this).val();
        if (metodo === "tarjeta") {
            $("#pago_tarjeta").removeClass("d-none");
            $("#pago_efectivo").addClass("d-none");
        } else if (metodo === "efectivo") {
            $("#pago_efectivo").removeClass("d-none");
            $("#pago_tarjeta").addClass("d-none");
        }
    });
});

function actualizarListaCarrito() {
    let carrito = JSON.parse(localStorage.getItem('carrito_tienda')) || [];
    let html = "";
    let total = 0;

    if (carrito.length === 0) {
        $("#lista_carrito").html("<tr><td colspan='5' class='text-center text-muted'>El carrito está vacío</td></tr>");
        $("#total_compra").text("$0.00");
        return;
    }

    carrito.forEach(function(item, index) {
        let subtotal = item.precio * item.cantidad;
        total += subtotal;
        html += `
            <tr>
                <td>${item.nombre}</td>
                <td>${item.cantidad}</td>
                <td>$${parseFloat(item.precio).toFixed(2)}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm btn-eliminar-carrito" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    $("#lista_carrito").html(html);
    $("#total_compra").text("$" + total.toFixed(2));

    // Evento eliminar del carrito
    $(document).off("click", ".btn-eliminar-carrito").on("click", ".btn-eliminar-carrito", function(){
        let index = $(this).data("index");
        carrito.splice(index, 1);
        localStorage.setItem('carrito_tienda', JSON.stringify(carrito));
        actualizarListaCarrito();
    });
}

function cargar_productos(cat, pag) {
    $.ajax({
        url: "ajax/ProductoAjax.php",
        method: "POST",
        data: { categoria_tienda: cat, pagina_tienda: pag },
        success: function(data) {
            $("#contenedor_productos_tienda").html(data);
        }
    });
}