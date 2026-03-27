<?php
session_start();
require_once "../configuracion/conexiones.php";

header('Content-Type: application/json');

if(isset($_POST['usuario_login'])){
    $db = Conexion::conectar();
    $user = $_POST['usuario_login'];
    $pass = $_POST['password_login'];

    $sql = $db->prepare("SELECT * FROM usuarios WHERE usuario = ? AND password = ? AND estado = 1");
    $sql->execute([$user, $pass]);
    $datos = $sql->fetch(PDO::FETCH_ASSOC);

    if($datos){
        $rol = trim(strtolower($datos['rol']));
        
        $_SESSION['usuario_id'] = $datos['id'];
        $_SESSION['usuario_nombre'] = $datos['nombre'];
        $_SESSION['usuario_rol'] = $rol;

        echo json_encode(["res" => "success", "rol" => $rol]);
    } else {
        echo json_encode(["res" => "error", "msj" => "Credenciales incorrectas"]);
    }
}