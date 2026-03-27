<?php
$peticionAjax = (isset($peticionAjax) && $peticionAjax);

if($peticionAjax){
    require_once "../modelo/ProvedorModelo.php";
} else {
    require_once "./modelo/ProvedorModelo.php";
}

class ProveedorControlador extends ProvedorModelo {

    /*--------- Listar Proveedores (Arregla Error Pantalla Blanca) ---------*/
    public function listar_proveedores_controlador() {
        return parent::listar_proveedores_modelo()->fetchAll(PDO::FETCH_ASSOC);
    }

    /*--------- Registro y Actualización ---------*/
    public function acciones_proveedor_controlador() {
        // Limpieza de datos usando el método de tu maestroModelo
        $id = parent::limpiar_cadena($_POST['id_proveedor']);
        $nombre = parent::limpiar_cadena($_POST['nombre_empresa']);
        $contacto = parent::limpiar_cadena($_POST['nombre_contacto']);
        $telefono = parent::limpiar_cadena($_POST['telefono']);
        $email = parent::limpiar_cadena($_POST['correo']);
        $direccion = parent::limpiar_cadena($_POST['direccion']);
        $modo = parent::limpiar_cadena($_POST['modo_prov']);

        if($nombre == ""){
            return ["res" => "error", "msj" => "El nombre es obligatorio"];
        }

        $datos = [
            "id" => $id,
            "nombre" => $nombre,
            "contacto" => $contacto,
            "telefono" => $telefono,
            "email" => $email,
            "direccion" => $direccion
        ];

        if($modo == "editar"){
            $res = parent::actualizar_proveedor_modelo($datos);
        } else {
            $res = parent::guardar_proveedor_modelo($datos);
        }

        return ["res" => $res ? "success" : "error"];
    }

    /*--------- Eliminar Proveedor ---------*/
    public function eliminar_proveedor_controlador($id) {
        $id = parent::limpiar_cadena($id);
        $res = parent::eliminar_proveedor_modelo($id);
        return ["res" => $res ? "success" : "error"];
    }
}