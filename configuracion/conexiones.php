<?php
class Conexion {
    public static function conectar() {
        $host = "localhost";
        $db   = "usuarios";
        $user = "root";
        $pass = "";
        
        try {
            $link = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            
            // Forzar a PDO a lanzar excepciones si algo sale mal en las consultas
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Configurar el set de caracteres
            $link->exec("set names utf8");
            
            return $link;
        } catch (PDOException $e) {
            // En producción, es mejor no mostrar el $e->getMessage() completo por seguridad
            die("Error de conexión: " . $e->getMessage());
        }
    }
}