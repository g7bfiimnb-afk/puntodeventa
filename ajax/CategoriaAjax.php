<?php
if (ob_get_level() > 0) ob_clean(); // Forma más segura de limpiar el buffer

require_once "../configuracion/conexiones.php";

header('Content-Type: application/json');

if(isset($_POST['nombre_categoria'])){
    $db = Conexion::conectar();
    
    // Limpiar el nombre para que sea un nombre de tabla válido (solo letras, números y guiones bajos)
    $nombre_original = trim($_POST['nombre_categoria']);
    $nombre = preg_replace('/[^a-zA-Z0-9_]/', '', $nombre_original);
    $nombre = strtolower($nombre); 

    if(empty($nombre)){
        echo json_encode(["res" => "error", "msj" => "Nombre no válido. Use solo letras y números."]);
        exit();
    }

    try {
        // SQL con los atributos exactos solicitados por el usuario
        $sql = "CREATE TABLE IF NOT EXISTS `$nombre` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `codigo_barras` varchar(50) DEFAULT NULL,
                  `nombre` varchar(150) NOT NULL,
                  `precio_compra` decimal(10,2) DEFAULT NULL,
                  `precio_venta` decimal(10,2) NOT NULL,
                  `stock` int(11) DEFAULT 0,
                  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
                  `imagen` MEDIUMBLOB,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `idx_codigo_$nombre` (`codigo_barras`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        $db->exec($sql);
        
        echo json_encode(["res" => "success", "msj" => "¡Categoría '$nombre' creada con éxito!"]);
    } catch (PDOException $e) {
        echo json_encode(["res" => "error", "msj" => "Error de base de datos: " . $e->getMessage()]);
    }
    exit();
}

// Acción para listar tablas que actúan como categorías (opcional para el select)
if(isset($_POST['listar_tablas'])){
    $db = Conexion::conectar();
    $stmt = $db->query("SHOW TABLES");
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Filtramos para no mostrar tablas del sistema
    $excluir = ['usuarios', 'ventas', 'detalle_ventas', 'proveedores'];
    $categorias = array_filter($tablas, function($t) use ($excluir) {
        return !in_array($t, $excluir);
    });

    echo json_encode(array_values($categorias));
    exit();
}
?>