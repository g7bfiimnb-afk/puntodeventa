<?php
require_once "maestroModelo.php";

class ProductoModelo extends maestroModelo {

    protected static function listar_productos_modelo() {
        $sql = parent::conectar()->prepare("SELECT * FROM productos ORDER BY nombre ASC");
        $sql->execute();
        return $sql;
    }

    protected static function guardar_producto_modelo($d) {
        $sql = parent::conectar()->prepare("INSERT INTO productos (codigo_barras, nombre, precio_compra, precio_venta, stock, categoria, imagen) VALUES (:cod, :nom, :pc, :pv, :st, :cat, :img)");
        $sql->bindParam(":cod", $d['codigo_barras']);
        $sql->bindParam(":nom", $d['nombre']);
        $sql->bindParam(":pc", $d['precio_compra']);
        $sql->bindParam(":pv", $d['precio_venta']);
        $sql->bindParam(":st", $d['stock']);
        $sql->bindParam(":cat", $d['categoria']);
        $sql->bindParam(":img", $d['imagen']);
        return $sql->execute();
    }

    protected static function actualizar_producto_modelo($d) {
        $sql = parent::conectar()->prepare("UPDATE productos SET codigo_barras=:cod, nombre=:nom, precio_compra=:pc, precio_venta=:pv, stock=:st, categoria=:cat, imagen=:img WHERE id=:id");
        $sql->bindParam(":id", $d['id']);
        $sql->bindParam(":cod", $d['codigo_barras']);
        $sql->bindParam(":nom", $d['nombre']);
        $sql->bindParam(":pc", $d['precio_compra']);
        $sql->bindParam(":pv", $d['precio_venta']);
        $sql->bindParam(":st", $d['stock']);
        $sql->bindParam(":cat", $d['categoria']);
        $sql->bindParam(":img", $d['imagen']);
        return $sql->execute();
    }

    protected static function eliminar_producto_modelo($id) {
        $sql = parent::conectar()->prepare("DELETE FROM productos WHERE id=:id");
        $sql->bindParam(":id", $id);
        return $sql->execute();
    }

    protected static function obtener_producto_por_id($id) {
        $sql = parent::conectar()->prepare("SELECT * FROM productos WHERE id=:id");
        $sql->bindParam(":id", $id);
        $sql->execute();
        return $sql;
    }
}