<?php
ob_start();
$peticionAjax = true;
require_once "../configuracion/conexiones.php";
require_once "../controladores/ProductoControlador.php";

$ins = new ProductoControlador();

// RECIBIDOR UNIFICADO PARA TIENDA (FILTRADO)
if (isset($_POST['categoria_tienda'])) {
    $categoria = $_POST['categoria_tienda'];
    $pagina = isset($_POST['pagina_tienda']) ? $_POST['pagina_tienda'] : 1;
    ob_end_clean();
    echo $ins->paginador_productos_controlador($categoria, $pagina);
    exit();
}

// RECIBIDOR PARA ELIMINAR O GUARDAR (ADMIN)
if (isset($_POST['id_eliminar_prod'])) {
    $res = $ins->eliminar_producto_controlador($_POST['id_eliminar_prod']);
} else {
    $res = $ins->acciones_producto_controlador();
}

ob_end_clean();
echo json_encode($res);