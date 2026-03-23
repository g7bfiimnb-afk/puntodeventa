<?php
$peticionAjax = true;
require_once "../controladores/ProductoControlador.php";

$insProducto = new ProductoControlador();

if(isset($_POST['codigo_barra'])) {
    // Si viene el código de barra, es para agregar
    echo $insProducto->agregar_producto_controlador();
} elseif(isset($_POST['id_eliminar'])) {
    // Si viene id_eliminar, es para borrar
    echo $insProducto->eliminar_producto_controlador($_POST['id_eliminar']);
}