<?php 
    $peticionAjax = false;
    // Cargamos el controlador
    require_once "./controladores/ProveedorControlador.php";
    $insProv = new ProveedorControlador();
    $datos = $insProv->listar_proveedores_controlador(); 
?>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-truck"></i> Directorio de Proveedores</span>
        <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalNuevo()">
            <i class="fas fa-plus-circle"></i> Agregar Proveedor
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tablaProveedores">
                <thead class="thead-light">
                    <tr>
                        <th>Nombre / Empresa</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Correo</th> <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($datos as $row): ?>
                    <tr>
                        <td><strong><?php echo $row['nombre']; ?></strong></td>
                        <td><?php echo $row['contacto']; ?></td>
                        <td><i class="fas fa-phone text-primary"></i> <?php echo $row['telefono']; ?></td>
                        <td><?php echo $row['email']; ?></td> <td><small><?php echo $row['direccion']; ?></small></td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-editar-prov" 
                                data-id="<?php echo $row['id']; ?>"
                                data-nombre="<?php echo $row['nombre']; ?>"
                                data-contacto="<?php echo $row['contacto']; ?>"
                                data-telefono="<?php echo $row['telefono']; ?>"
                                data-correo="<?php echo $row['email']; ?>"
                                data-direccion="<?php echo $row['direccion']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm btn-eliminar-prov" data-id="<?php echo $row['id']; ?>">
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

<div class="modal fade" id="modalProveedor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tituloModal">Nuevo Proveedor</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="form_proveedor_ajax">
                <div class="modal-body">
                    <input type="hidden" name="id_proveedor" id="id_proveedor">
                    <input type="hidden" name="modo_prov" id="modo_prov" value="nuevo">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nombre de la Empresa</label>
                            <input type="text" name="nombre_empresa" id="nombre_empresa" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Nombre del Contacto</label>
                            <input type="text" name="nombre_contacto" id="nombre_contacto" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Correo</label>
                            <input type="email" name="correo" id="correo" class="form-control">
                        </div>
                        <div class="col-12 form-group">
                            <label>Dirección / Notas</label>
                            <textarea name="direccion" id="direccion" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>