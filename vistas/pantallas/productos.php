<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-boxes"></i> Inventario Actual</h5>
                <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalNuevoProducto">
                    <i class="fas fa-plus-circle"></i> Nuevo Producto
                </button>
            </div>
            
            <div class="card-body p-3"> <div class="table-responsive">
                    <table id="tablaInventario" class="table table-hover mb-0 w-100">
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
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoProducto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Agregar Nuevo Producto</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_producto_ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Código de Barras</label>
                        <input type="text" name="codigo_barra" class="form-control" placeholder="Escanear o escribir..." required>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre del Producto</label>
                        <input type="text" name="nombre_prod" class="form-control" placeholder="Ej: Jabón Zote" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Categoría</label>
                        <select name="producto_categoria" class="form-control" required>
                            <option value="" selected disabled>Seleccione una categoría</option>
                            <option value="Alimentos básicos">Alimentos básicos</option>
                            <option value="Enlatados y conservas">Enlatados y conservas</option>
                            <option value="Lácteos">Lácteos</option>
                            <option value="Embutidos y carnes frías">Embutidos y carnes frías</option>
                            <option value="Panadería y galletas">Panadería y galletas</option>
                            <option value="Botanas y dulces">Botanas y dulces</option>
                            <option value="Bebidas">Bebidas</option>
                            <option value="Productos de limpieza">Productos de limpieza</option>
                            <option value="Higiene personal">Higiene personal</option>
                            <option value="Frutas y verduras">Frutas y verduras</option>
                            <option value="Abarrotes varios">Abarrotes varios</option>
                            <option value="Artículos desechables">Artículos desechables</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label class="font-weight-bold">P. Compra</label>
                            <input type="number" name="p_compra" step="0.01" class="form-control" value="0.00">
                        </div>
                        <div class="col-6">
                            <label class="font-weight-bold">P. Venta</label>
                            <input type="number" name="p_venta" step="0.01" class="form-control" value="0.00" required>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="font-weight-bold">Stock Inicial</label>
                        <input type="number" name="stock" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="./vistas/js/productos.js"></script>