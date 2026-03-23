<?php
$peticionAjax = true;
require_once "../controladores/VentaControlador.php";

if(isset($_POST['action'])) {
    $insVenta = new VentaControlador();

    // Acción para buscar un producto y agregarlo
    if($_POST['action'] == "buscar_producto") {
        if(isset($_POST['codigo'])){
            echo $insVenta->buscar_producto_controlador($_POST['codigo']);
        }
    }

    // Aquí puedes agregar más acciones, como:
    // if($_POST['action'] == "registrar_venta") { ... }

} else {
    // Si alguien intenta entrar directo al archivo por URL
    session_start();
    session_destroy();
    header("Location: ../index.php");
    exit();
}