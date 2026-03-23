<?php
$peticionAjax = true;
require_once "../controladores/VentaControlador.php";

$insVenta = new VentaControlador();

// 1. Acción para buscar un producto (Cuando usas la lupa o Enter)
if(isset($_POST['buscar_codigo'])) {
    echo $insVenta->buscar_producto_venta_controlador($_POST['buscar_codigo']);
    exit(); // Importante para detener la ejecución aquí
}

// 2. Acción para finalizar la venta (Cuando das clic en Cobrar)
if(isset($_POST['productos_venta'])) {
    echo $insVenta->guardar_venta_controlador();
    exit();
}

// Si no es ninguna de las anteriores y alguien intenta entrar por URL
if(!isset($_POST['buscar_codigo']) && !isset($_POST['productos_venta'])){
    session_start();
    session_destroy();
    header("Location: ../login/"); // O la ruta de tu login
    exit();
}