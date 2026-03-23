<?php
$peticionAjax = true;
require_once "../controladores/LoginControlador.php";

if(isset($_POST['usuario_login']) && isset($_POST['password_login'])) {
    $insLogin = new LoginControlador();
    echo $insLogin->iniciar_sesion_controlador();
} else {
    session_start();
    session_destroy();
    header("Location: ../index.php");
    exit();
}