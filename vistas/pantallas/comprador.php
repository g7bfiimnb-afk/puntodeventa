<?php
    require_once "./controladores/ProductoControlador.php";
    $insP = new ProductoControlador();
?>
<div class="container mt-4">
    <div class="row bg-dark text-white p-3 mb-4 rounded shadow align-items-center">
        <div class="col">
            <h4 class="mb-0 text-warning"><i class="fas fa-store"></i> EL GÜERO - TIENDA</h4>
        </div>
        <div class="col text-right">
            <a href="ajax/Logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-8">
            <div class="btn-group" role="group" aria-label="categorias">
                <button class="btn btn-outline-primary btn-sm btn-categoria" data-cat="Todas">Todas</button>
                <button class="btn btn-outline-primary btn-sm btn-categoria" data-cat="Abarrotes">Abarrotes</button>
                <button class="btn btn-outline-primary btn-sm btn-categoria" data-cat="Bebidas">Bebidas</button>
                <button class="btn btn-outline-primary btn-sm btn-categoria" data-cat="Lácteos">Lácteos</button>
                <button class="btn btn-outline-primary btn-sm btn-categoria" data-cat="Panadería">Panadería</button>
            </div>
            <button class="btn btn-outline-success btn-sm ml-2" id="btn_ofertas">Ofertas (Top stock)</button>
        </div>
        <div class="col-md-4 text-right">
            <button id="btn_ver_carrito" class="btn btn-warning btn-sm"><i class="fas fa-shopping-cart"></i> Ver Carrito (<span id="num_cart">0</span>)</button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-muted mb-0">Basado en tu última visita</h4>
    </div>

    <div class="row" id="contenedor_productos_tienda">
        <?php 
            // Esto genera los cuadros automáticamente
            echo $insP->paginador_productos_controlador("General", 1); 
        ?>
    </div>
</div>

<!-- Modal Carrito -->
<div class="modal fade" id="modalCarrito" tabindex="-1" role="dialog" aria-labelledby="modalCarritoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCarritoLabel"><i class="fas fa-shopping-cart"></i> Carrito de Compras</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-right">Precio</th>
                                <th class="text-right">Subtotal</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="lista_carrito">
                            <tr><td colspan="5" class="text-center text-muted">El carrito está vacío</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group mt-3">
                    <label for="metodo_pago"><strong>Método de pago</strong></label>
                    <select id="metodo_pago" class="form-control">
                        <option value="tarjeta">💳 Tarjeta de Crédito/Débito</option>
                        <option value="efectivo">💵 Efectivo</option>
                    </select>
                </div>

                <div id="pago_tarjeta" class="p-3 border rounded bg-light">
                    <div class="form-group">
                        <label>Número de tarjeta</label>
                        <input type="text" class="form-control" id="tarjeta_num" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>MM/AA</label>
                            <input type="text" class="form-control" id="tarjeta_exp" placeholder="12/30">
                        </div>
                        <div class="form-group col-md-6">
                            <label>CVV</label>
                            <input type="text" class="form-control" id="tarjeta_cvv" placeholder="123">
                        </div>
                    </div>
                </div>

                <div id="pago_efectivo" class="p-3 border rounded bg-light d-none">
                    <p class="text-muted">Pago en efectivo al momento de la entrega.</p>
                </div>

                <div class="text-right mt-3">
                    <h5 class="font-weight-bold">Total: <span id="total_compra" class="text-success">$0.00</span></h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Seguir comprando</button>
                <button type="button" class="btn btn-success" id="btn_confirmar_compra"><i class="fas fa-check-circle"></i> Confirmar compra</button>
            </div>
        </div>
    </div>
</div>