<?php
require_once "../configuracion/conexiones.php";

class VentaModelo extends Conexion {

    /**
     * Busca un producto por código de barras o ID
     */
    protected static function buscar_producto_modelo($codigo) {
        $sql = "SELECT id, nombre, precio_venta, stock, codigo_barras 
                FROM productos 
                WHERE codigo_barras = :codigo OR id = :codigo 
                AND stock > 0";
        
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":codigo", $codigo);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registra la venta principal en la tabla 'ventas'
     */
    protected static function registrar_venta_modelo($datos) {
        $sql = "INSERT INTO ventas (folio, fecha, total, id_usuario) 
                VALUES (:folio, :fecha, :total, :id_usuario)";
        
        $db = Conexion::conectar();
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam(":folio", $datos['folio']);
        $stmt->bindParam(":fecha", $datos['fecha']);
        $stmt->bindParam(":total", $datos['total']);
        $stmt->bindParam(":id_usuario", $datos['id_usuario']);
        
        if($stmt->execute()){
            return $db->lastInsertId(); // Retornamos el ID para los detalles
        }
        return "error";
    }

    /**
     * Actualiza el inventario restando lo vendido
     */
    protected static function actualizar_stock_modelo($id_producto, $cantidad) {
        $sql = "UPDATE productos SET stock = stock - :cantidad WHERE id = :id_producto";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":id_producto", $id_producto);
        return $stmt->execute();
    }
}

<?php
require_once "../configuracion/conexiones.php";

class LoginModelo extends Conexion {
    protected static function iniciar_sesion_modelo($datos) {
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND estado = 1";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":usuario", $datos['usuario']);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}