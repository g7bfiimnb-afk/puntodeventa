<?php
require_once "../configuracion/conexiones.php";

$db = Conexion::conectar();

$orden = $_POST['orden'] ?? 'nombre_asc';
$buscar = $_POST['buscar'] ?? '';
$tabla_seleccionada = $_POST['tabla_seleccionada'] ?? 'all'; // Nuevo parámetro para filtrar por tabla

$productos = [];
$query_tables = [];

// Determinar qué tablas consultar
if ($tabla_seleccionada === 'all') {
    // Si se selecciona "Todas las Categorías", obtenemos todas las tablas de productos
    $stmtTablas = $db->query("SHOW TABLES FROM " . $db->query("SELECT DATABASE()")->fetchColumn());
    $todasLasTablas = $stmtTablas->fetchAll(PDO::FETCH_COLUMN);
    $excluir = ['usuarios', 'ventas', 'detalle_ventas', 'proveedores'];
    $query_tables = array_filter($todasLasTablas, function($t) use ($excluir) {
        return !in_array($t, $excluir);
    });
} else {
    // Si se selecciona una tabla específica, solo consultamos esa tabla (sanitizando el nombre)
    $query_tables[] = preg_replace('/[^a-zA-Z0-9_]/', '', $tabla_seleccionada);
}

$order_clause = "";
switch ($orden) {
    case 'nombre_asc': $order_clause = "ORDER BY nombre ASC"; break;
    case 'nombre_desc': $order_clause = "ORDER BY nombre DESC"; break;
    case 'precio_min': $order_clause = "ORDER BY precio_venta ASC"; break;
    case 'precio_max': $order_clause = "ORDER BY precio_venta DESC"; break;
    case 'stock_max': $order_clause = "ORDER BY stock DESC"; break;
    default: $order_clause = "ORDER BY nombre ASC"; break;
}

foreach ($query_tables as $tabla) {
    try {
        $sql = "SELECT * FROM `$tabla` WHERE nombre LIKE :buscar $order_clause";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':buscar', '%' . $buscar . '%', PDO::PARAM_STR);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregamos el nombre de la tabla a cada producto para identificar su origen en el carrito
        foreach($items as $k => $v){
            $items[$k]['tabla_origen'] = $tabla;
        }
        $productos = array_merge($productos, $items);
    } catch (PDOException $e) {
        // Si la tabla no tiene la estructura de productos (ej. falta columna 'nombre'), la saltamos
        continue;
    }
}

// Renderizamos las tarjetas de productos
if(count($productos) > 0):
    foreach($productos as $prod):
        ?>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm border-0 card-producto" style="border-radius: 15px; transition: transform 0.3s;">
                <div class="card-body text-center d-flex flex-column align-items-center">
                    <?php
                        // Ajustamos la ruta de la imagen para que sea correcta desde el contexto de AJAX
                        $foto = (!empty($prod['imagen'])) ? "vistas/img/productos/" . $prod['imagen'] : "vistas/img/productos/default.png";
                        $ruta_mostrar = (file_exists("../" . $foto)) ? $foto : "vistas/img/productos/default.png";
                    ?>
                    <img src="<?php echo $ruta_mostrar; ?>" class="img-fluid mb-3 rounded" style="height: 160px; width: 100%; object-fit: contain;">

                    <h6 class="card-title font-weight-bold text-dark mb-1"><?php echo $prod['nombre']; ?></h6>
                    <span class="badge badge-light text-muted mb-2">Stock: <?php echo $prod['stock']; ?></span>
                    <p class="h5 text-success mb-2 font-weight-bold">$ <?php echo number_format($prod['precio_venta'], 2); ?></p>

                    <button class="btn btn-warning btn-block btn-sm mt-auto text-dark font-weight-bold btn-agregar-carrito shadow-sm" style="border-radius: 10px;" data-id="<?php echo $prod['id']; ?>" data-tabla="<?php echo $prod['tabla_origen']; ?>">
                        <i class="fas fa-cart-plus"></i> Agregar
                    </button>
                </div>
            </div>
        </div>
        <?php
    endforeach;
else:
    ?>
    <div class="col-12 text-center py-5">
        <i class="fas fa-box-open fa-4x text-light mb-3"></i>
        <p class="text-muted">No encontramos productos en el inventario.</p>
    </div>
    <?php
endif;
?>