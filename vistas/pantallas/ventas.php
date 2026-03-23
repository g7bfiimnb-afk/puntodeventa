<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="input-group input-group-lg">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-barcode"></i></span>
                    </div>
                    <input type="text" id="buscar_producto" class="form-control" placeholder="Escanea el código de barras aquí...">
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong><i class="fas fa-shopping-cart"></i> Carrito de Venta</strong></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th width="100">Cant.</th>
                            <th>Subtotal</th>
                            <th></th>
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
                <h5>TOTAL A PAGAR</h5>
                <h1 class="display-3 font-weight-bold">$ <span id="total_venta">0.00</span></h1>
                <button class="btn btn-success btn-lg btn-block mt-3" id="btn_finalizar_venta">
                    <i class="fas fa-money-bill-wave"></i> COBRAR AHORA
                </button>
            </div>
        </div>
    </div>
</div>