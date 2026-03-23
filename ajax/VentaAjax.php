<?php
// 1. Configuraciones iniciales
$peticionAjax = true;
session_start();

// 2. Importar el controlador
// Verifica que la ruta y las mayúsculas sean exactas: ../controladores/VentaControlador.php
require_once "../controladores/VentaControlador.php";

$insVenta = new VentaControlador();

/**
 * ACCIÓN: BUSCAR PRODUCTO
 * Se activa al presionar Enter o clic en la lupa
 */
if(isset($_POST['buscar_codigo'])) {
    // Limpiamos cualquier salida accidental (espacios o errores previos)
    if (ob_get_length()) ob_clean(); 
    
    // Llamamos a la función del controlador
    echo $insVenta->buscar_producto_venta_controlador($_POST['buscar_codigo']);
    exit();
}

/**
 * ACCIÓN: FINALIZAR VENTA (COBRAR)
 * Se activa al presionar el botón verde "COBRAR AHORA"
 */
if(isset($_POST['productos_venta']) && isset($_POST['total_venta'])) {
    // Verificamos que la sesión siga activa antes de procesar el pago
    if(!isset($_SESSION['usuario_id'])){
        echo json_encode([
            "res" => "error", 
            "msj" => "Error: La sesión ha expirado. Inicia sesión de nuevo para cobrar."
        ]);
        exit();
    }

    if (ob_get_length()) ob_clean();
    echo $insVenta->guardar_venta_controlador();
    exit();
}

/**
 * PROTECCIÓN: Si intentan entrar al archivo directamente por la URL
 */
if(!isset($_POST['buscar_codigo']) && !isset($_POST['productos_venta'])){
    header("Location: ../index.php");
    exit();
}