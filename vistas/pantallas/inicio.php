<div class="container-fluid">
    <div class="jumbotron jumbotron-fluid bg-white shadow-sm border rounded p-4">
        <div class="container">
            <h1 class="display-4 text-primary">
                <i class="fas fa-store"></i> ¡Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?>!
            </h1>
            <p class="lead">Panel de control de Abarrotes "Mi Tiendita".</p>
            <hr class="my-4">
            
            <div class="row text-center">
                
                <div class="col-md-4 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ventas de Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    // Reutilizamos la conexión para sumar el total de ventas del día actual
                                    require_once "configuracion/conexiones.php";
                                    $db = Conexion::conectar();
                                    
                                    // SQL que suma la columna 'total' solo de la fecha actual
                                    $ventasHoy = $db->query("SELECT SUM(total) FROM ventas WHERE DATE(fecha) = CURDATE()")->fetchColumn();
                                    
                                    // Si no hay ventas hoy, mostrar 0.00
                                    echo "$ " . number_format(($ventasHoy ? $ventasHoy : 0), 2);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Productos Registrados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $totalProd = $db->query("SELECT count(*) FROM productos")->fetchColumn();
                                    echo $totalProd;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <a href="index.php?p=ventas" class="btn btn-primary btn-block p-3 shadow-sm h-100 d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                        <span class="mt-2 font-weight-bold">Ir a Caja de Cobro</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>