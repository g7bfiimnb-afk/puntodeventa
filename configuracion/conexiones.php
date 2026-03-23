<?php
class Conexion {
    public static function conectar() {
        $host = "localhost";
        $db   = "usuarios";
        $user = "root";
        $pass = "";
        
        try {
            $link = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $link->exec("set names utf8");
            return $link;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}