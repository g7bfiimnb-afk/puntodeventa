<?php
class maestroModelo {
    protected static function conectar() {
        try {
            // Debe coincidir con tu esquema real, aquí usas 'usuarios'
            $link = new PDO("mysql:host=localhost;dbname=usuarios;charset=utf8", "root", "");
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $link;
        } catch (PDOException $e) {
            error_log('[maestroModelo] conexión fallida: ' . $e->getMessage());
            return null;
        }
    }

    protected static function limpiar_cadena($cadena){
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        $cadena = str_ireplace("<script>", "", $cadena);
        $cadena = str_ireplace("</script>", "", $cadena);
        return $cadena;
    }
}