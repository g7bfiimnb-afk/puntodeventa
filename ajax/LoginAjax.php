<?php
session_start();
require_once "../configuracion/conexiones.php";

// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Limpiamos cualquier salida previa para asegurar un JSON limpio
ob_clean();
header('Content-Type: application/json');

if(isset($_POST['usuario_login'])){
    $db = Conexion::conectar();
    
    // Es buena práctica usar las llaves del POST tal cual vienen del formulario
    $user = $_POST['usuario_login'];
    $pass = $_POST['password_login'];

    $sql = $db->prepare("SELECT * FROM usuarios WHERE usuario = ? AND password = ?");
    $sql->execute([$user, $pass]);
    $datos = $sql->fetch(PDO::FETCH_ASSOC);

    if($datos){
        // Normalizar rol para evitar valores vacíos o con mayúsculas/minúsculas.
        $rol = trim(strtolower($datos['rol'] ?? ''));
        if(empty($rol)) {
            // Fallback en caso de que el registro no tenga rol asignado.
            // Ajusta la lógica según tu modelo de usuarios.
            $rol = ($datos['usuario'] === 'cliente01') ? 'comprador' : 'admin';
        }

        // 2. Llenamos la sesión con los datos del usuario
        $_SESSION['usuario_id'] = $datos['id'];
        $_SESSION['usuario_nombre'] = $datos['nombre'];
        $_SESSION['usuario_rol'] = $rol;

        // 3. Enviamos la respuesta exitosa incluyendo el ROL
        echo json_encode([
            "res" => "success", 
            "rol" => $rol
        ]);
    } else {
        // 4. Respuesta en caso de error de credenciales
        echo json_encode([
            "res" => "error", 
            "msj" => "Usuario o contraseña incorrectos"
        ]);
    }
} else {
    echo json_encode([
        "res" => "error", 
        "msj" => "No se recibieron datos del formulario"
    ]);
}
exit(); // Importante para detener la ejecución aquí