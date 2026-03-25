<?php
if(file_exists("../configuracion/conexiones.php")){
    require_once "../configuracion/conexiones.php";
} else {
    require_once "./configuracion/conexiones.php";
}

class ProductoModelo extends Conexion {

    protected static function agregar_producto_modelo($datos) {
        $sql = "INSERT INTO productos (codigo_barras, nombre, precio_compra, precio_venta, stock) 
                VALUES (:codigo, :nombre, :p_compra, :p_venta, :stock)";
        
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":codigo", $datos['codigo']);
        $stmt->bindParam(":nombre", $datos['nombre']);
        $stmt->bindParam(":p_compra", $datos['p_compra']);
        $stmt->bindParam(":p_venta", $datos['p_venta']);
        $stmt->bindParam(":stock", $datos['stock']);
        
        return $stmt->execute();
    }

    protected static function consultar_productos_modelo() {
        $sql = "SELECT * FROM productos ORDER BY nombre ASC";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Añade esto dentro de la clase ProductoModelo
    protected static function eliminar_producto_modelo($id) {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    protected static function guardar_producto_modelo($datos) {
    $sql = Conexion::conectar()->prepare("INSERT INTO productos 
        (codigo_barras, nombre, precio_venta, stock, categoria_id) 
        VALUES (:codigo, :nombre, :precio, :stock, :categoria)");

    $sql->bindParam(":codigo", $datos['codigo']);
    $sql->bindParam(":nombre", $datos['nombre']);
    $sql->bindParam(":precio", $datos['precio']);
    $sql->bindParam(":stock", $datos['stock']);
    $sql->bindParam(":categoria", $datos['categoria']); // Nuevo campo

    return $sql->execute();
    }
}