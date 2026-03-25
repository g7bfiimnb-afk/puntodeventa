<?php
// Si no es un comprador, lo mandamos al inicio administrativo
if($_SESSION['usuario_rol'] != 'comprador'){
    header("Location: index.php?p=inicio");
    exit();
}
?>
<div class="comprador-container bg-light min-vh-100">
    
    <header class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-warning" href="index.php?p=comprador">
                <i class="fas fa-store"></i> Abarrotes "El Güero"
            </a>

            <form class="form-inline mx-auto w-50" id="form_buscar_comprador">
                <div class="input-group w-100 shadow-sm">
                    <input type="text" class="form-control border-0 p-3" id="buscar_producto" placeholder="Buscar jabón, leche, pan..." aria-label="Buscar">
                    <div class="input-group-append">
                        <button class="btn btn-warning border-0 text-dark" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="navbar-nav ml-auto align-items-center text-white">
                <span class="mr-3 badge badge-pill badge-primary p-2">
                    <i class="fas fa-user-circle"></i> <?php echo $_SESSION['usuario_nombre']; ?>
                </span>
                
                <a href="#" class="text-white text-decoration-none position-relative mr-3" title="Mi Carrito">
                    <i class="fas fa-shopping-cart fa-lg text-warning"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-danger" style="font-size: 0.7rem; top: -10px; right: -10px;">
                        3
                    </span>
                </a>
                
                <a href="ajax/Logout.php" class="btn btn-outline-danger btn-sm ml-2">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </header>

    <main class="container py-5">
        
        <div class="mb-5">
            <h4 class="text-muted mb-4">Productos en Oferta de Hoy</h4>
            <div class="row" id="contenedor_ofertas">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100 shadow-sm border-0 card-producto">
                        <div class="card-body text-center d-flex flex-column align-items-center">
                            <i class="fas fa-box fa-3x text-secondary mb-3 mt-3"></i> <p class="card-title font-weight-bold text-dark mb-1">Aceite Vegetal 900ml</p>
                            <p class="h5 text-success mb-2 font-weight-bold">$ 42.50 <small class="text-muted strike text-danger" style="text-decoration: line-through;">$45.00</small></p>
                            <button class="btn btn-warning btn-block btn-sm mt-auto text-dark font-weight-bold">
                                <i class="fas fa-cart-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100 shadow-sm border-0 card-producto">
                        <div class="card-body text-center d-flex flex-column align-items-center">
                            <i class="fas fa-box fa-3x text-secondary mb-3 mt-3"></i>
                            <p class="card-title font-weight-bold text-dark mb-1">Leche Entera 1L</p>
                            <p class="h5 text-success mb-2 font-weight-bold">$ 24.00</p>
                            <button class="btn btn-warning btn-block btn-sm mt-auto text-dark font-weight-bold">
                                <i class="fas fa-cart-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100 shadow-sm border-0 card-producto">
                        <div class="card-body text-center d-flex flex-column align-items-center">
                            <i class="fas fa-box fa-3x text-secondary mb-3 mt-3"></i>
                            <p class="card-title font-weight-bold text-dark mb-1">Pan de Caja Bimbo</p>
                            <p class="h5 text-success mb-2 font-weight-bold">$ 48.00</p>
                            <button class="btn btn-warning btn-block btn-sm mt-auto text-dark font-weight-bold">
                                <i class="fas fa-cart-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100 shadow-sm border-0 card-producto">
                        <div class="card-body text-center d-flex flex-column align-items-center">
                            <i class="fas fa-box fa-3x text-secondary mb-3 mt-3"></i>
                            <p class="card-title font-weight-bold text-dark mb-1">Huevo Blanco 1kg</p>
                            <p class="h5 text-success mb-2 font-weight-bold">$ 55.00</p>
                            <button class="btn btn-warning btn-block btn-sm mt-auto text-dark font-weight-bold">
                                <i class="fas fa-cart-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white py-3 border-top mt-5">
        <div class="container text-center text-muted">
            <small>&copy; 2026 Abarrotes "El Güero" - Punto de Venta para Clientes</small>
        </div>
    </footer>
</div>