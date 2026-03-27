<?php 
    $peticionAjax = false;
    require_once "controladores/ProductoControlador.php";
    $insProd = new ProductoControlador();
    $datos_consulta = $insProd->listar_productos_controlador(); 
?>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-boxes"></i> Inventario de Productos</span>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalProducto" onclick="abrirModalNuevo()">
            <i class="fas fa-plus-circle"></i> Nuevo Producto
        </button>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if($datos_consulta->rowCount() >= 1){
                    foreach($datos_consulta->fetchAll() as $row){ 
                ?>
                <tr>
                    <td><img src="vistas/imagenes/<?php echo $row['imagen']; ?>" width="50" class="img-thumbnail"></td>
                    <td><?php echo $row['codigo_barras']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm btn-editar-prod" 
                            data-id="<?php echo $row['id']; ?>"
                            data-codigo="<?php echo $row['codigo_barras']; ?>"
                            data-nombre="<?php echo $row['nombre']; ?>"
                            data-pcompra="<?php echo $row['precio_compra']; ?>"
                            data-pventa="<?php echo $row['precio_venta']; ?>"
                            data-stock="<?php echo $row['stock']; ?>"
                            data-cat="<?php echo $row['categoria']; ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-eliminar-prod" data-id="<?php echo $row['id']; ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalProducto" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_producto_ajax" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_producto" id="id_producto">
                <input type="hidden" name="modo_prod" id="modo_prod">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Producto</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Código de Barras</label>
                            <input type="text" name="codigo_barras" id="codigo_barras" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>P. Compra</label>
                            <input type="text" name="precio_compra" id="precio_compra" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>P. Venta</label>
                            <input type="text" name="precio_venta" id="precio_venta" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" id="stock" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Categoría</label>
                            <select name="categoria" id="categoria" class="form-control">
                                <option>General</option>
                                <option>Abarrotes</option>
                                <option>Bebidas</option>
                                <option>Lácteos</option>
                                <option>Panadería</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Imagen</label>
                            <input type="file" name="foto_producto" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>