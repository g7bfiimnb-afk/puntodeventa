<?php
require_once "../configuracion/conexiones.php";

// Acción para GUARDAR un proveedor
if(isset($_POST['nombre_prov'])){
    $db = Conexion::conectar();
    
    $nombre = $_POST['nombre_prov'];
    $contacto = $_POST['contacto_prov'];
    $tel = $_POST['telefono_prov'];
    $email = $_POST['email_prov'];
    $dir = $_POST['direccion_prov'];

    $sql = $db->prepare("INSERT INTO proveedores (nombre, contacto, telefono, email, direccion) VALUES (?,?,?,?,?)");
    
    if($sql->execute([$nombre, $contacto, $tel, $email, $dir])){
        echo json_encode(["res" => "success", "msj" => "¡Proveedor guardado con éxito!"]);
    } else {
        echo json_encode(["res" => "error", "msj" => "Hubo un error al intentar guardar."]);
    }
    exit();
}

// Acción para ELIMINAR un proveedor
if(isset($_POST['id_eliminar'])){
    $db = Conexion::conectar();
    $sql = $db->prepare("DELETE FROM proveedores WHERE id = ?");
    if($sql->execute([$_POST['id_eliminar']])){
        echo json_encode(["res" => "success", "msj" => "Proveedor eliminado."]);
    }
    exit();
}