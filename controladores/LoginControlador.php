<?php

// Detectamos si la petición viene por AJAX para ajustar la ruta del require
if(isset($peticionAjax) && $peticionAjax){
    require_once "../modelo/LoginModelo.php";
}else{
    require_once "./modelo/LoginModelo.php";
}

class LoginControlador extends LoginModelo {

    /**
     * Controlador para iniciar sesión (Sin encriptación)
     */
    public function iniciar_sesion_controlador() {
        
        // Limpiamos los datos recibidos del formulario (POST)
        $usuario = trim($_POST['usuario_login']);
        $password = trim($_POST['password_login']);

        // Validaciones básicas de campos vacíos
        if($usuario == "" || $password == ""){
            return json_encode([
                "res" => "error",
                "msj" => "Por favor rellene todos los campos"
            ]);
        }

        // Preparamos los datos para enviarlos al Modelo
        $datosLogin = [
            "usuario" => $usuario,
            "password" => $password
        ];

        // Llamamos a la función del modelo (que ahora busca usuario Y password juntos)
        $datosUsuario = parent::iniciar_sesion_modelo($datosLogin);

        if($datosUsuario) {
            
            // Si el modelo devolvió datos, iniciamos la sesión de PHP
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Guardamos los datos importantes en la sesión
            $_SESSION['usuario_id'] = $datosUsuario['id'];
            $_SESSION['usuario_nombre'] = $datosUsuario['nombre'];
            $_SESSION['usuario_rol'] = $datosUsuario['rol'];

            // Respuesta de éxito para el JavaScript (login.js)
            return json_encode([
                "res" => "success",
                "msj" => "¡Bienvenido, " . $datosUsuario['nombre'] . "!"
            ]);

        } else {
            // Si el modelo no encontró nada (datos incorrectos o usuario inactivo)
            return json_encode([
                "res" => "error",
                "msj" => "El usuario o la contraseña son incorrectos"
            ]);
        }
    }

    /**
     * Función para cerrar sesión (Opcional si no usas Logout.php por separado)
     */
    public function cerrar_sesion_controlador() {
        session_start();
        session_destroy();
        return header("Location: ../index.php");
    }
}