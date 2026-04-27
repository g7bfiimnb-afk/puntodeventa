<?php
// 1. Seguridad de Rol
if($_SESSION['usuario_rol'] != 'comprador'){
    echo "<script>window.location.href='index.php?p=inicio';</script>";
    exit();
}

// 2. Conexión y Consulta Inicial (Por defecto A-Z)
require_once "configuracion/conexiones.php";
$db = Conexion::conectar();
$query = $db->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="comprador-container bg-light min-vh-100">

    <header class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-warning" href="index.php?p=comprador">
                <i class="fas fa-store"></i> Abarrotes "El Güero"
            </a>
            <form class="form-inline mx-auto w-50" id="form_buscar_comprador">
                <div class="input-group w-100 shadow-sm">
                    <input type="text" class="form-control border-0 p-3" id="buscar_producto_input" placeholder="Buscar productos..." aria-label="Buscar">
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
                <button type="button" class="btn btn-warning text-dark font-weight-bold position-relative mr-3" id="btn_ver_carrito" title="Mi Carrito">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                    <span class="badge badge-danger position-absolute" id="badge_carrito" style="top:-10px; right:-10px; border-radius:50%;">0</span>
                </button>
                <a href="ajax/Logout.php" class="btn btn-outline-danger btn-sm ml-2">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </header>

    <main class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 shadow-sm rounded">
            <h4 class="text-muted mb-0">Catálogo de Productos</h4>
            <div class="form-inline">
                <label class="mr-2 font-weight-bold">Ordenar por:</label>
                <select class="form-control form-control-sm" id="ordenar_catalogo">
                    <option value="nombre_asc">Nombre (A-Z)</option>
                    <option value="nombre_desc">Nombre (Z-A)</option>
                    <option value="precio_min">Precio (Más bajo)</option>
                    <option value="precio_max">Precio (Más alto)</option>
                    <option value="stock_max">Más existencias</option>
                </select>
            </div>
        </div>

        <div class="row" id="contenedor_productos_catalogo">
            <?php if(count($productos) > 0): ?>
                <?php foreach($productos as $prod): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 card-producto">
                            <div class="card-body text-center d-flex flex-column align-items-center">
                                <?php 
                                    $foto = (!empty($prod['imagen'])) ? "vistas/img/productos/" . $prod['imagen'] : "vistas/img/productos/default.png";
                                    $ruta_mostrar = (file_exists($foto)) ? $foto : "vistas/img/productos/default.png";
                                ?>
                                <img src="<?php echo $ruta_mostrar; ?>" class="img-fluid mb-3" style="height: 150px; width: 100%; object-fit: contain;">
                                
                                <p class="card-title font-weight-bold text-dark mb-1"><?php echo $prod['nombre']; ?></p>
                                <p class="text-muted small mb-2">Existencia: <?php echo $prod['stock']; ?></p>
                                <p class="h5 text-success mb-2 font-weight-bold">$ <?php echo number_format($prod['precio_venta'], 2); ?></p>
                                
                                <button class="btn btn-warning btn-block btn-sm mt-auto text-dark font-weight-bold btn-agregar-carrito" data-id="<?php echo $prod['id']; ?>">
                                    <i class="fas fa-cart-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open fa-4x text-light mb-3"></i>
                    <p class="text-muted">No encontramos productos en el inventario.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-white py-3 border-top mt-5">
        <div class="container text-center text-muted">
            <small>&copy; 2026 Abarrotes "El Güero" - Punto de Venta</small>
        </div>
    </footer>

    <!-- MODAL DEL CARRITO COMPRADOR -->
    <div class="modal fade" id="modalCarritoComprador" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning text-dark font-weight-bold">
                    <h5 class="modal-title" id="modalLabel">
                        <i class="fas fa-shopping-cart"></i> Mi Carrito de Compras
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="contenedor_carrito">
                        <p class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            Tu carrito está vacío
                        </p>
                    </div>
                </div>
                <div class="modal-body bg-light border-top">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Total a Pagar:</label>
                                <h4 class="text-success font-weight-bold" id="total_carrito_resumen">$ 0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-lg" id="btn_procesar_compra">
                        <i class="fas fa-check-circle"></i> Procesar Compra
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN PERSONALIZADO -->
    <div class="modal fade" id="modalConfirmacionCompra" tabindex="-1" role="dialog" aria-labelledby="modalConfirmLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-xl" style="border-radius: 15px;">
                <div class="modal-header border-0 bg-gradient" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title text-white font-weight-bold" id="modalConfirmLabel">
                        <i class="fas fa-receipt fa-2x mr-2"></i>Ticket de Compra
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-receipt fa-3x text-success mb-3" style="opacity: 0.8;"></i>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6 text-right">
                            <span class="text-muted">Total a Pagar:</span>
                        </div>
                        <div class="col-6 text-left">
                            <strong class="h5 text-dark" id="conf_total">$ 0.00</strong>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6 text-right">
                            <span class="text-muted">Dinero Recibido:</span>
                        </div>
                        <div class="col-6 text-left">
                            <strong class="h5 text-primary" id="conf_recibido">$ 0.00</strong>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="row">
                        <div class="col-6 text-right">
                            <span class="font-weight-bold text-success">Cambio a Entregar:</span>
                        </div>
                        <div class="col-6 text-left">
                            <h5 class="text-success font-weight-bold mb-0" id="conf_cambio">$ 0.00</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-success btn-lg" id="btn_confirmar_compra">
                        <i class="fas fa-check mr-2"></i>Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE INFORMACIÓN PERSONAL Y MÉTODO DE PAGO -->
    <div class="modal fade" id="modalInfoPersonalPago" tabindex="-1" role="dialog" aria-labelledby="modalInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-white font-weight-bold">
                    <h5 class="modal-title" id="modalInfoLabel">
                        <i class="fas fa-user-edit mr-2"></i> Información del Cliente
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <form id="form_info_personal_pago">
                        <!-- Identificación del Usuario -->
                        <div class="card mb-3 border-info">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-id-card mr-2"></i> Identificación del Usuario
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="cliente_nombre">Nombre Completo *</label>
                                            <input type="text" class="form-control form-control-lg" id="cliente_nombre" value="Victor Eduardo Lopez Soriano" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="cliente_id_usuario">Nombre de Usuario (ID)</label>
                                            <input type="text" class="form-control form-control-lg" id="cliente_id_usuario" value="HAEFCDBGH13783" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="cliente_nombre_elegido">Nombre Elegido (Display Name)</label>
                                            <input type="text" class="form-control form-control-lg" id="cliente_nombre_elegido" value="Victor Eduardo Lopez">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="cliente_ocupacion">Ocupación</label>
                                            <input type="text" class="form-control form-control-lg" id="cliente_ocupacion" value="Estudiante">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Datos de Facturación (Fiscales) -->
                        <div class="card mb-3 border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <i class="fas fa-file-invoice-dollar mr-2"></i> Datos de Facturación (Fiscales)
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="cliente_rfc">RFC</label>
                                            <input type="text" class="form-control form-control-lg" id="cliente_rfc" placeholder="Opcional o CURP">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="cliente_situacion_fiscal">Situación Fiscal</label>
                                            <input type="text" class="form-control form-control-lg" id="cliente_situacion_fiscal" placeholder="Opcional">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="card mb-3 border-success">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-address-book mr-2"></i> Información de Contacto
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="cliente_email">Correo Electrónico *</label>
                                            <input type="email" class="form-control form-control-lg" id="cliente_email" value="el638883@gmail.com" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="cliente_telefono">Teléfono Celular *</label>
                                            <input type="tel" class="form-control form-control-lg" id="cliente_telefono" value="+52 638883" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold" for="cliente_direccion">Dirección del Domicilio</label>
                                    <input type="text" class="form-control form-control-lg" id="cliente_direccion" placeholder="Calle, número, colonia, ciudad">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold" for="cliente_referencias">Referencias del Domicilio</label>
                                    <textarea class="form-control form-control-lg" id="cliente_referencias" rows="3" placeholder="Puntos de referencia, referencias adicionales, puertas, entre calles"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Método de Pago -->
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-credit-card mr-2"></i> Método de Pago
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">Selecciona tu método de pago *</label>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="pago_efectivo" name="metodo_pago" value="efectivo" checked>
                                        <label class="custom-control-label" for="pago_efectivo">
                                            <i class="fas fa-money-bill-wave text-success"></i> Efectivo
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="pago_tarjeta" name="metodo_pago" value="tarjeta_credito">
                                        <label class="custom-control-label" for="pago_tarjeta">
                                            <i class="fas fa-credit-card text-primary"></i> Tarjeta de Crédito/Débito
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="pago_transferencia" name="metodo_pago" value="transferencia">
                                        <label class="custom-control-label" for="pago_transferencia">
                                            <i class="fas fa-exchange-alt text-warning"></i> Transferencia Bancaria
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-success btn-lg" id="btn_finalizar_compra">
                        <i class="fas fa-check-circle mr-2"></i> Finalizar Compra
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./vistas/js/comprador.js"></script>