<?php
class Conexion {
    public static function conectar() {
        $host = "localhost";
        $db   = "usuarios";
        $user = "root";
        $pass = "";
        
        try {
            $link = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $link;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}