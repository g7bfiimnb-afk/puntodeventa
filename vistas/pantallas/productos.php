<?php
// Conexión y consulta (esto ya lo debes tener arriba)
require_once "configuracion/conexiones.php";
$db = Conexion::conectar();
$query = $db->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h5 class="mb-0"><i class="fas fa-boxes"></i> Inventario de Productos</h5>
        <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalProducto" id="btnNuevoProducto">
            <i class="fas fa-plus-circle"></i> Nuevo Producto
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered" id="tablaProductos">
                <thead class="thead-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Venta</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($productos as $prod): ?>
                    <tr>
                        <td><?php echo $prod['codigo_barras']; ?></td>
                        <td><?php echo $prod['nombre']; ?></td>
                        <td>$<?php echo number_format($prod['precio_venta'], 2); ?></td>
                        <td><?php echo $prod['stock']; ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btn-editar-prod" 
                                    data-id="<?php echo $prod['id']; ?>"
                                    data-foto="<?php echo $prod['imagen']; ?>"
                                    title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm btn-eliminar-prod" data-id="<?php echo $prod['id']; ?>" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="tituloModal">Nuevo Producto</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            
            <form id="form_producto_ajax" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_producto" id="id_producto">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Código</label>
                                <input type="text" name="codigo_prod" id="codigo_prod" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stock Inicial</label>
                                <input type="number" name="stock_prod" id="stock_prod" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nombre del Producto</label>
                        <input type="text" name="nombre_prod" id="nombre_prod" class="form-control form-control-sm" placeholder="Ej: Sabritas Original 45g" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Precio Compra</label>
                                <input type="number" step="0.01" name="p_compra_prod" id="p_compra_prod" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Precio Venta</label>
                                <input type="number" step="0.01" name="p_venta_prod" id="p_venta_prod" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group border-top pt-3 mt-3">
                        <label class="font-weight-bold"><i class="fas fa-image"></i> Imagen del Producto</label>
                        <div class="d-flex align-items-center">
                            <img src="./vistas/img/productos/default.png" id="previsualizarFoto" class="img-thumbnail mr-3" style="height: 80px; width: 80px; object-fit: contain;">
                            
                            <div class="custom-file custom-file-sm">
                                <input type="file" name="foto_prod" id="foto_prod" class="custom-file-input" accept="image/jpeg,image/jpg,image/png,image/webp,image/avif">
                                <label class="custom-file-label" for="foto_prod">Seleccionar archivo...</label>
                                <small class="text-muted">Max: 2MB. Formatos: jpg, png, webp, avif.</small>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>