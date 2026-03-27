<?php
$peticionAjax = true;
require_once "../configuracion/conexiones.php";

if(isset($_POST['nombre_empresa']) || isset($_POST['id_eliminar_prov'])){
    require_once "../controladores/ProveedorControlador.php";
    $insProv = new ProveedorControlador();

    if(isset($_POST['id_eliminar_prov'])){
        echo json_encode($insProv->eliminar_proveedor_controlador($_POST['id_eliminar_prov']));
    } else {
        echo json_encode($insProv->acciones_proveedor_controlador());
    }
} else {
    echo json_encode(["res" => "error", "msj" => "Acceso denegado"]);
}