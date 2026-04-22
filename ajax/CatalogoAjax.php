<?php
require_once "../configuracion/conexiones.php";
$db = Conexion::conectar();

// 1. Recibimos el orden Y el término de búsqueda (si existen)
$orden = $_POST['orden'] ?? 'nombre_asc';
$buscar = $_POST['buscar'] ?? ''; 

// 2. Mapeo seguro para el ORDER BY
switch ($orden) {
    case 'nombre_desc': $sql_orden = "nombre DESC"; break;
    case 'precio_min':  $sql_orden = "precio_venta ASC"; break;
    case 'precio_max':  $sql_orden = "precio_venta DESC"; break;
    case 'stock_max':   $sql_orden = "stock DESC"; break;
    default:            $sql_orden = "nombre ASC"; break;
}

// 3. Construimos la consulta dinámica
if (!empty($buscar)) {
    // Si hay búsqueda, usamos LIKE para filtrar por nombre o código de barras
    $query = $db->prepare("SELECT * FROM productos 
                           WHERE nombre LIKE :busq 
                           OR codigo_barras LIKE :busq 
                           ORDER BY $sql_orden");
    $termino = "%$buscar%";
    $query->bindParam(':busq', $termino);
    $query->execute();
} else {
    // Si no hay búsqueda, traemos todo ordenado
    $query = $db->query("SELECT * FROM productos ORDER BY $sql_orden");
}

$productos = $query->fetchAll(PDO::FETCH_ASSOC);

// 4. Generamos el HTML (Idéntico al original pero funcional con el buscador)
if(count($productos) > 0) {
    foreach($productos as $prod) {
        $foto = (!empty($prod['imagen'])) ? "vistas/img/productos/" . $prod['imagen'] : "vistas/img/productos/default.png";
        $ruta_verificar = "../" . $foto;
        $ruta_final = (file_exists($ruta_verificar)) ? $foto : "vistas/img/productos/default.png";
        
        echo '
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm border-0 card-producto">
                <div class="card-body text-center d-flex flex-column align-items-center">
                    <img src="'.$ruta_final.'" class="img-fluid mb-3" style="height: 150px; width: 100%; object-fit: contain;">
                    
                    <p class="card-title font-weight-bold text-dark mb-1">'.$prod['nombre'].'</p>
                    <p class="text-muted small mb-2">Existencia: '.$prod['stock'].'</p>
                    <p class="h5 text-success mb-2 font-weight-bold">$ '.number_format($prod['precio_venta'], 2).'</p>
                    
                    <button class="btn btn-warning btn-block btn-sm mt-auto text-dark font-weight-bold btn-agregar-carrito" 
                            data-id="'.$prod['id'].'">
                        <i class="fas fa-cart-plus"></i> Agregar
                    </button>
                </div>
            </div>
        </div>';
    }
} else {
    echo '<div class="col-12 text-center py-5">
            <i class="fas fa-search fa-4x text-light mb-3"></i>
            <p class="text-muted">No se encontraron productos para "'.htmlspecialchars($buscar).'".</p>
          </div>';
}