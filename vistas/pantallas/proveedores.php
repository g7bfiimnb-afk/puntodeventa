<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-truck text-warning"></i> Directorio de Proveedores</h5>
                <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalNuevoProveedor">
                    <i class="fas fa-plus-circle"></i> Agregar Proveedor
                </button>
            </div>
            
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="tablaProveedores" class="table table-hover table-bordered mb-0 w-100">
                        <thead class="thead-light">
                            <tr>
                                <th>Nombre / Empresa</th>
                                <th>Contacto</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                require_once "configuracion/conexiones.php";
                                $db = Conexion::conectar();
                                $res = $db->query("SELECT * FROM proveedores ORDER BY nombre ASC");
                                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                                    echo "<tr>
                                        <td class='font-weight-bold'>{$row['nombre']}</td>
                                        <td>{$row['contacto']}</td>
                                        <td><a href='tel:{$row['telefono']}' class='text-primary'><i class='fas fa-phone'></i> {$row['telefono']}</a></td>
                                        <td><small class='text-muted'>{$row['direccion']}</small></td>
                                        <td class='text-center'>
                                            <button class='btn btn-info btn-sm' title='Editar'><i class='fas fa-edit'></i></button>
                                            <button class='btn btn-danger btn-sm btn-eliminar-prov' data-id='{$row['id']}'><i class='fas fa-trash'></i></button>
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

<div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-truck"></i> Nuevo Proveedor</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="form_proveedor_ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre de la Empresa</label>
                        <input type="text" name="nombre_prov" class="form-control" placeholder="Ej: Coca-Cola México" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre del Contacto</label>
                        <input type="text" name="contacto_prov" class="form-control" placeholder="Ej: Ing. Alberto Rojas">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Teléfono</label>
                                <input type="text" name="telefono_prov" class="form-control" placeholder="953 123 4567">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Correo</label>
                                <input type="email" name="email_prov" class="form-control" placeholder="ejemplo@proveedor.com">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Dirección / Notas</label>
                        <textarea name="direccion_prov" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>