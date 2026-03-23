<div class="jumbotron jumbotron-fluid bg-white shadow-sm border rounded">
  <div class="container">
    <h1 class="display-4 text-primary">Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?></h1>
    <p class="lead">Este es el resumen de tu tiendita para el día de hoy.</p>
    <hr class="my-4">
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas de Hoy</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">$0.00</div>
                </div>
            </div>
        </div>
        </div>
  </div>
</div>