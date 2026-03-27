<?php
require_once "maestroModelo.php";

class TiendaModelo extends maestroModelo {

    // Traer productos por categoría para los cuadros
    protected static function obtener_productos_modelo($categoria, $inicio) {
        $conexion = mainModel::conectar(); // Usamos la conexión del maestro
        $sql = $conexion->prepare("SELECT * FROM productos WHERE categoria = :cat LIMIT :inicio, 12");
        
        $sql->bindParam(":cat", $categoria);
        // PDO requiere que el LIMIT sea tratado como entero
        $sql->bindValue(":inicio", (int)$inicio, PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    // El buscador tipo "Mercado Libre"
    protected static function buscar_productos_modelo($busqueda) {
        $busqueda = mainModel::limpiar_cadena($busqueda);
        $sql = mainModel::conectar()->prepare("SELECT * FROM productos WHERE nombre LIKE :busqueda OR codigo_barras LIKE :busqueda LIMIT 12");
        $termino = "%".$busqueda."%";
        $sql->bindParam(":busqueda", $termino);
        $sql->execute();
        return $sql;
    }
}