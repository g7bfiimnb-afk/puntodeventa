<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <label for="buscar_producto"><strong><i class="fas fa-search"></i> Buscar Producto</strong></label>
                <div class="input-group input-group-lg">
                    <input type="text" id="buscar_producto" class="form-control border-primary" placeholder="Escribe el nombre o escanea el código..." autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="btn_buscar_lupa">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <small class="text-muted">Presiona Enter para agregar al carrito automáticamente.</small>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <strong><i class="fas fa-shopping-cart"></i> Carrito de Venta</strong>
                <button class="btn btn-outline-danger btn-sm" id="vaciar_carrito"><i class="fas fa-trash-alt"></i> Vaciar</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0" id="tabla_venta_actual">
                    <thead class="thead-light">
                        <tr>
                            <th>Producto</th>
                            <th width="150">Precio ($)</th>
                            <th width="180">Cantidad / Unidad</th>
                            <th width="120">Subtotal</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="lista_venta">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm bg-dark text-white text-center">
            <div class="card-body">
                <h5 class="text-muted">TOTAL A PAGAR</h5>
                <h1 class="display-3 font-weight-bold" id="total_venta_display">$ 0.00</h1>
                <input type="hidden" id="total_venta_input" value="0">
                
                <button class="btn btn-success btn-lg btn-block mt-3 py-3 font-weight-bold shadow" id="btn_finalizar_venta">
                    <i class="fas fa-money-bill-wave"></i> COBRAR AHORA
                </button>
            </div>
        </div>
        
        <div id="info_producto_seleccionado" class="mt-3">
            </div>
    </div>
</div>

<div class="modal fade" id="modalCobrar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-hand-holding-usd"></i> Procesar Pago</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted mb-1">TOTAL A PAGAR</p>
                <h2 class="text-dark font-weight-bold mb-4" id="m_total_pagar">$ 0.00</h2>
                
                <div class="form-group text-left">
                    <label class="font-weight-bold">Efectivo Recibido:</label>
                    <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0">$</span>
                        </div>
                        <input type="number" id="m_efectivo_recibido" class="form-control border-left-0 pl-0 font-weight-bold" placeholder="0.00" autofocus>
                    </div>
                </div>

                <div class="alert alert-primary mt-4 py-3">
                    <p class="mb-0 small font-weight-bold text-uppercase">Cambio a entregar:</p>
                    <h1 class="mb-0 font-weight-bold" id="m_cambio">$ 0.00</h1>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success px-4" id="m_btn_confirmar_venta">
                    <i class="fas fa-check-circle"></i> CONFIRMAR VENTA
                </button>
            </div>
        </div>
    </div>
</div>