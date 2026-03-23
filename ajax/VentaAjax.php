<?php
$peticionAjax = true;
require_once "../controladores/VentaControlador.php";

$insVenta = new VentaControlador();

// 1. Buscar producto (Lupa o Enter)
if(isset($_POST['buscar_codigo'])) {
    ob_start(); // Inicia un "escudo" para atrapar errores de texto
    $respuesta = $insVenta->buscar_producto_venta_controlador($_POST['buscar_codigo']);
    ob_end_clean(); // Borra cualquier error que haya saltado (como los <br> o <b>)
    echo $respuesta;
    exit();
}

// 2. Finalizar venta (Botón Cobrar)
if(isset($_POST['productos_venta'])) {
    ob_start();
    $respuesta = $insVenta->guardar_venta_controlador();
    ob_end_clean();
    echo $respuesta;
    exit();
}

// Si no es ninguna, redirigir
session_start();
if(!isset($_SESSION['usuario_id'])){
    header("Location: ../index.php");
    exit();
}