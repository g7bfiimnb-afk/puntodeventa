<?php
// 1. Seguridad de Rol
if($_SESSION['usuario_rol'] != 'comprador'){
    echo "<script>window.location.href='index.php?p=inicio';</script>";
    exit();
}

// 2. Conexión y Consulta de Productos Reales
require_once "configuracion/conexiones.php";
$db = Conexion::conectar();
$query = $db->query("SELECT * FROM productos ORDER BY id DESC");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<img src="<?php echo (file_exists('vistas/img/productos/'.$prod['imagen'])) ? 'vistas/img/productos/'.$prod['imagen'] : 'vistas/img/productos/proximamente.png'; ?>" class="card-img-top">

<div class="comprador-container bg-light min-vh-100">
    
    <header class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-warning" href="index.php?p=comprador">
                <i class="fas fa-store"></i> Abarrotes "El Güero"
            </a>

            <form class="form-inline mx-auto w-50" id="form_buscar_comprador">
                <div class="input-group w-100 shadow-sm">
                    <input type="text" class="form-control border-0 p-3" id="buscar_producto" placeholder="Buscar productos..." aria-label="Buscar">
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
                    <span class="badge badge-danger position-absolute" style="top:-10px; right:-10px; border-radius:50%;">0</span>
                </a>
                <a href="ajax/Logout.php" class="btn btn-outline-danger btn-sm ml-2">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </header>

    <main class="container py-5">
        <div class="mb-5">
            <h4 class="text-muted mb-4">Catálogo de Productos</h4>
            <div class="row" id="contenedor_ofertas">
                
                <?php if(count($productos) > 0): ?>
                    <?php foreach($productos as $prod): ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm border-0 card-producto">
                                <div class="card-body text-center d-flex flex-column align-items-center">
                                    
                                    <?php 
                                        $foto = (!empty($prod['imagen'])) ? "vistas/img/productos/" . $prod['imagen'] : "";
                                        if(file_exists($foto) && !empty($prod['imagen'])): 
                                    ?>
                                        <img src="<?php echo $foto; ?>" class="img-fluid mb-3" style="height: 150px; object-fit: contain;">
                                    <?php else: ?>
                                        <i class="fas fa-box fa-3x text-secondary mb-3 mt-3"></i>
                                    <?php endif; ?>

                                    <p class="card-title font-weight-bold text-dark mb-1"><?php echo $prod['nombre']; ?></p>
                                    <p class="text-muted small mb-2">Stock: <?php echo $prod['stock']; ?></p>
                                    <p class="h5 text-success mb-2 font-weight-bold">$ <?php echo number_format($prod['precio_venta'], 2); ?></p>
                                    
                                    <button class="btn btn-warning btn-block btn-sm mt-auto text-dark font-weight-bold btn-agregar-carrito" data-id="<?php echo $prod['id']; ?>">
                                        <i class="fas fa-cart-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No hay productos registrados en el inventario.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer class="bg-white py-3 border-top mt-5">
        <div class="container text-center text-muted">
            <small>&copy; 2026 Abarrotes "El Güero" - Punto de Venta</small>
        </div>
    </footer>
</div>