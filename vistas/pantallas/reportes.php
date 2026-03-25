<?php
    // 1. Capturamos el filtro de la URL
    $filtro = (isset($_GET['f'])) ? $_GET['f'] : 'dia';
    $titulo_reporte = "Reporte de " . ucfirst($filtro);

    // 2. Importamos la conexión
    require_once "configuracion/conexiones.php";
    $db = Conexion::conectar();

    // 3. Condiciones SQL para VENTAS
    $condicionVentas = "DATE(fecha) = CURDATE()"; 
    if($filtro == 'semana') $condicionVentas = "YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)";
    if($filtro == 'mes')    $condicionVentas = "MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())";
    if($filtro == 'anio')   $condicionVentas = "YEAR(fecha) = YEAR(CURDATE())";

    // 4. Condiciones SQL para PRODUCTOS
    $condicionProd = "DATE(fecha_registro) = CURDATE()"; 
    if($filtro == 'semana') $condicionProd = "YEARWEEK(fecha_registro, 1) = YEARWEEK(CURDATE(), 1)";
    if($filtro == 'mes')    $condicionProd = "MONTH(fecha_registro) = MONTH(CURDATE()) AND YEAR(fecha_registro) = YEAR(CURDATE())";
    if($filtro == 'anio')   $condicionProd = "YEAR(fecha_registro) = YEAR(CURDATE())";

    // 5. CÁLCULOS PARA LAS TARJETAS DE RESUMEN
    $resumen = $db->query("SELECT SUM(total) as gran_total, COUNT(*) as total_filas FROM ventas WHERE $condicionVentas")->fetch(PDO::FETCH_ASSOC);
    $sumaDinero = ($resumen['gran_total']) ? $resumen['gran_total'] : 0;
    $cantidadVentas = $resumen['total_filas'];
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-light d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-dark"><i class="fas fa-chart-line text-primary"></i> Centro de Reportes</h4>
                    <div class="btn-group" role="group">
                        <a href="index.php?p=reportes&f=dia" class="btn btn-outline-primary <?php echo ($filtro == 'dia') ? 'active' : ''; ?>">Hoy</a>
                        <a href="index.php?p=reportes&f=semana" class="btn btn-outline-primary <?php echo ($filtro == 'semana') ? 'active' : ''; ?>">Semana</a>
                        <a href="index.php?p=reportes&f=mes" class="btn btn-outline-primary <?php echo ($filtro == 'mes') ? 'active' : ''; ?>">Mes</a>
                        <a href="index.php?p=reportes&f=anio" class="btn btn-outline-primary <?php echo ($filtro == 'anio') ? 'active' : ''; ?>">Año</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ingresos Totales (<?php echo $filtro; ?>)</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">$ <?php echo number_format($sumaDinero, 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ventas Realizadas</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $cantidadVentas; ?> Tickets</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-ventas-tab" data-toggle="pill" href="#pills-ventas" role="tab">
                    <i class="fas fa-cash-register"></i> Ventas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-productos-tab" data-toggle="pill" href="#pills-productos" role="tab">
                    <i class="fas fa-boxes"></i> Nuevos Productos
                </a>
            </li>
        </ul>

        <button onclick="window.print();" class="btn btn-danger shadow-sm">
            <i class="fas fa-file-pdf"></i> Generar Reporte (PDF)
        </button>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-ventas" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold text-primary">
                    <i class="fas fa-list"></i> Detalle de Ventas - <?php echo $titulo_reporte; ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 w-100">
                            <thead class="thead-light">
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha y Hora</th>
                                    <th>Total Cobrado</th>
                                    <th>Atendió</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $ventas = $db->query("SELECT * FROM ventas WHERE $condicionVentas ORDER BY id DESC");
                                    $resV = $ventas->fetchAll(PDO::FETCH_ASSOC);
                                    if(count($resV) > 0){
                                        foreach($resV as $v){
                                            echo "<tr>
                                                <td><span class='badge badge-dark'>#{$v['id']}</span></td>
                                                <td>".date('d/m/Y H:i', strtotime($v['fecha']))."</td>
                                                <td class='text-success font-weight-bold'>$ ".number_format($v['total'], 2)."</td>
                                                <td><i class='fas fa-user-circle text-muted'></i> {$_SESSION['usuario_nombre']}</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='text-center p-5 text-muted'>No se encontraron ventas.</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-productos" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold text-primary">
                    <i class="fas fa-plus-square"></i> Registros Nuevos - <?php echo $titulo_reporte; ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 w-100">
                            <thead class="thead-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    try {
                                        $productos = $db->query("SELECT * FROM productos WHERE $condicionProd ORDER BY id DESC");
                                        $resP = $productos->fetchAll(PDO::FETCH_ASSOC);
                                        if(count($resP) > 0){
                                            foreach($resP as $p){
                                                echo "<tr>
                                                    <td><code>{$p['codigo_barras']}</code></td>
                                                    <td>{$p['nombre']}</td>
                                                    <td><span class='badge badge-info'>{$p['categoria']}</span></td>
                                                    <td class='font-weight-bold'>$ ".number_format($p['precio_venta'], 2)."</td>
                                                    <td><span class='badge badge-light border'>{$p['stock']}</span></td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' class='text-center p-5 text-muted'>No hay registros nuevos.</td></tr>";
                                        }
                                    } catch (Exception $e) {
                                        echo "<tr><td colspan='5' class='text-center p-4 text-danger'>Falta columna fecha_registro.</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>