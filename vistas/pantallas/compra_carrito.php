<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold">Carrito de Ventas</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr>
                        </thead>
                        <tbody id="lista_carrito"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="card p-4 bg-light border-0">
                <h5>Total a Pagar</h5>
                <h2 class="font-weight-bold" id="total_compra">$ 0.00</h2>
                <hr>
                <button class="btn btn-dark btn-block shadow-sm mb-2"><i class="fab fa-cc-visa"></i> Pagar con Tarjeta</button>
                <button class="btn btn-primary btn-block shadow-sm"><i class="fab fa-paypal"></i> PayPal</button>
            </div>
        </div>
    </div>
</div>
<script src="./vistas/js/carrito_logica.js"></script>