<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5><i class="fas fa-barcode"></i> Escanear o Buscar</h5>
                    <div class="form-group">
                        <input type="text" id="buscar_producto" class="form-control" placeholder="Nombre o código de barras...">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Carrito de Venta</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="tabla_venta">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th width="100">Cant.</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="detalle_venta">
                            </tbody>
                    </table>
                    <hr>
                    <div class="text-right">
                        <h3>Total: $<span id="total_pagar">0.00</span></h3>
                        <button class="btn btn-success btn-lg" id="btn_finalizar_venta">
                            <i class="fas fa-cash-register"></i> Cobrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>