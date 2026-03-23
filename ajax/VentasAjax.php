<?php
$peticionAjax = true;
require_once "../controladores/VentaControlador.php";

$insVenta = new VentaControlador();

// Escuchar cuando el JS pide buscar un producto por código
if(isset($_POST['buscar_codigo'])) {
    echo $insVenta->buscar_producto_venta_controlador($_POST['buscar_codigo']);
} 

// Escuchar cuando el JS pide guardar la venta final (Lo usaremos después)
if(isset($_POST['productos_venta'])) {
    echo $insVenta->guardar_venta_controlador();
}