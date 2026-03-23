<?php
require_once "../configuracion/conexiones.php";

class LoginModelo extends Conexion {

    protected static function iniciar_sesion_modelo($datos) {
        // La consulta debe estar DENTRO de la función
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND password = :password AND estado = 1";
        
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":usuario", $datos['usuario']);
        $stmt->bindParam(":password", $datos['password']);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}