<?php
require_once "maestroModelo.php";

class ProvedorModelo extends maestroModelo {

    protected static function listar_proveedores_modelo() {
        $sql = parent::conectar()->prepare("SELECT * FROM proveedores ORDER BY nombre ASC");
        $sql->execute();
        return $sql;
    }

    protected static function guardar_proveedor_modelo($d) {
        $sql = parent::conectar()->prepare("INSERT INTO proveedores (nombre, contacto, telefono, email, direccion) VALUES (:nom, :con, :tel, :ema, :dir)");
        $sql->bindParam(":nom", $d['nombre']);
        $sql->bindParam(":con", $d['contacto']);
        $sql->bindParam(":tel", $d['telefono']);
        $sql->bindParam(":ema", $d['email']);
        $sql->bindParam(":dir", $d['direccion']);
        return $sql->execute();
    }

    protected static function actualizar_proveedor_modelo($d) {
        $sql = parent::conectar()->prepare("UPDATE proveedores SET nombre=:nom, contacto=:con, telefono=:tel, email=:ema, direccion=:dir WHERE id=:id");
        $sql->bindParam(":nom", $d['nombre']);
        $sql->bindParam(":con", $d['contacto']);
        $sql->bindParam(":tel", $d['telefono']);
        $sql->bindParam(":ema", $d['email']);
        $sql->bindParam(":dir", $d['direccion']);
        $sql->bindParam(":id", $d['id']);
        return $sql->execute();
    }

    protected static function eliminar_proveedor_modelo($id) {
        $sql = parent::conectar()->prepare("DELETE FROM proveedores WHERE id=:id");
        $sql->bindParam(":id", $id);
        return $sql->execute();
    }
}