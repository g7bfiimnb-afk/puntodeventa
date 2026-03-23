<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Nuevo Producto</h5>
            </div>
            <div class="card-body">
                <form id="form_producto_ajax">
                    <div class="form-group">
                        <label>Código de Barras</label>
                        <input type="text" name="codigo_barra" class="form-control" placeholder="Escanear o escribir..." required>
                    </div>
                    <div class="form-group">
                        <label>Nombre del Producto</label>
                        <input type="text" name="nombre_prod" class="form-control" placeholder="Ej: Jabón Zote" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>P. Compra</label>
                            <input type="number" name="p_compra" step="0.01" class="form-control" value="0.00">
                        </div>
                        <div class="col-6">
                            <label>P. Venta</label>
                            <input type="number" name="p_venta" step="0.01" class="form-control" value="0.00" required>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label>Stock Inicial</label>
                        <input type="number" name="stock" class="form-control" value="0">
                    </div>
                    <button type="submit" class="btn btn-success btn-block shadow-sm">
                        <i class="fas fa-save"></i> Guardar Producto
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-boxes"></i> Inventario Actual</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            require_once "controladores/ProductoControlador.php";
                            $ins = new ProductoControlador();
                            $datos = $ins->listar_productos_controlador();
                            
                            if(count($datos) > 0){
                                foreach($datos as $row){
                                    echo "<tr>
                                        <td>{$row['codigo_barras']}</td>
                                        <td>{$row['nombre']}</td>
                                        <td>$".number_format($row['precio_venta'], 2)."</td>
                                        <td><span class='badge badge-info p-2'>{$row['stock']}</span></td>
                                        <td class='text-center'>
                                            <button class='btn btn-danger btn-sm btn-eliminar' data-id='{$row['id']}' title='Eliminar'>
                                                <i class='fas fa-trash-alt'></i>
                                            </button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center p-4 text-muted'>No hay productos registrados aún.</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="./vistas/js/productos.js"></script>