<?php
// Conexión y consulta (esto ya lo debes tener arriba)
require_once "configuracion/conexiones.php";
$db = Conexion::conectar();

// Determinamos qué tabla visualizar (por defecto 'productos')
$tabla_actual = (isset($_GET['cat'])) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['cat']) : 'productos';

// Obtenemos la lista de tablas para el selector de categorías
$stmtTablas = $db->query("SHOW TABLES FROM " . $db->query("SELECT DATABASE()")->fetchColumn()); // Corregido para especificar la base de datos
$todasLasTablas = $stmtTablas->fetchAll(PDO::FETCH_COLUMN);
$excluir = ['usuarios', 'ventas', 'detalle_ventas', 'proveedores'];
$categorias = array_filter($todasLasTablas, function($t) use ($excluir) {
    return !in_array($t, $excluir);
});

$query = $db->query("SELECT * FROM `$tabla_actual` ORDER BY nombre ASC");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h5 class="mb-0"><i class="fas fa-boxes"></i> Inventario: <span class="badge badge-warning"><?php echo $tabla_actual; ?></span></h5>
        <div>
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-filter"></i> Ver Categoría
                </button>
                <div class="dropdown-menu">
                    <?php foreach($categorias as $cat): ?>
                        <a class="dropdown-item <?php echo ($cat == $tabla_actual) ? 'active' : ''; ?>" href="index.php?p=productos&cat=<?php echo $cat; ?>"><?php echo ucfirst($cat); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="button" class="btn btn-info btn-sm mr-1" data-toggle="modal" data-target="#modalCategoria"><i class="fas fa-folder-plus"></i> Categorías</button>
            <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalProducto" id="btnNuevoProducto"><i class="fas fa-plus-circle"></i> Nuevo Producto</button>
        </div>
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
                    <!-- Campo oculto para saber a qué tabla enviar los datos -->
                    <input type="hidden" name="tabla_destino" value="<?php echo $tabla_actual; ?>">
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

<!-- MODAL NUEVA CATEGORÍA (NUEVA TABLA) -->
<div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Crear Categoría</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre de la Categoría</label>
                    <input type="text" id="nombre_categoria_input" class="form-control" placeholder="Ej: Lacteos">
                    <small class="text-muted">Se creará una nueva tabla en la BD.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info btn-sm" id="btn_guardar_categoria">Crear Ahora</button>
            </div>
        </div>
    </div>
</div>

<script>
// Usamos una función que se autoejecute para esperar a jQuery
(function waitJQ() {
    if (window.jQuery) {
        $(document).ready(function(){
            // Lógica para crear la categoría (tabla)
            $('#btn_guardar_categoria').on('click', function(){
                let input = $('#nombre_categoria_input');
                let nombre = input.val().trim();
                
                if(nombre == ""){ alert("Por favor, escribe un nombre para la categoría."); return; }

                $.ajax({
                    url: 'ajax/CategoriaAjax.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { nombre_categoria: nombre },
                    success: function(r){
                        if(r.res == "success"){
                            alert(r.msj);
                            location.reload(); 
                        } else {
                            alert("Error: " + r.msj);
                        }
                    },
                    error: function(xhr){
                        console.error("Error del servidor:", xhr.responseText);
                        alert("No se pudo crear la categoría. Revisa la consola (F12) para más detalles.");
                    }
                });
            });
        });
    } else {
        setTimeout(waitJQ, 50); // Reintenta cada 50ms hasta que jQuery cargue
    }
})();
</script>