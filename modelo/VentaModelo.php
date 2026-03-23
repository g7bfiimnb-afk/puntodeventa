<?php
require_once "../configuracion/conexiones.php";

class VentaModelo extends Conexion {

    /**
     * Busca un producto por código de barras o por ID
     * Se usa para el buscador de la caja
     */
    protected static function buscar_producto_modelo($busqueda) {
        $db = Conexion::conectar();
        // Buscamos por código de barras exacto o coincidencia en el nombre
        $sql = $db->prepare("SELECT id, nombre, precio_venta, stock, codigo_barras 
                             FROM productos 
                             WHERE codigo_barras = :busqueda 
                             OR nombre LIKE CONCAT('%', :busqueda, '%') 
                             LIMIT 1");
        
        $sql->bindParam(":busqueda", $busqueda);
        $sql->execute();
        
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Proceso completo de venta: Inserta cabecera, detalles y resta stock
     * Todo dentro de una transacción para evitar datos incompletos
     */
    protected static function guardar_venta_modelo($datos) {
        $db = Conexion::conectar();
        
        try {
            $db->beginTransaction(); // Iniciamos protección de datos

            // 1. Insertar la cabecera de la venta
            $sqlVenta = $db->prepare("INSERT INTO ventas (total, usuario_id) VALUES (:total, :usuario)");
            $sqlVenta->bindParam(":total", $datos['total']);
            $sqlVenta->bindParam(":usuario", $datos['usuario_id']);
            $sqlVenta->execute();

            $idVenta = $db->lastInsertId(); // Obtenemos el ID de la venta recién creada

            // 2. Insertar cada producto y RESTAR stock
            foreach ($datos['productos'] as $prod) {
                // Registro del detalle
                $sqlDetalle = $db->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_venta) 
                                           VALUES (:v_id, :p_id, :cant, :precio)");
                $sqlDetalle->execute([
                    ":v_id" => $idVenta,
                    ":p_id" => $prod['id'],
                    ":cant" => $prod['cantidad'],
                    ":precio" => $prod['precio_venta']
                ]);

                // RESTAR STOCK en la tabla productos
                $sqlStock = $db->prepare("UPDATE productos SET stock = stock - :cant WHERE id = :p_id");
                $sqlStock->execute([
                    ":cant" => $prod['cantidad'],
                    ":p_id" => $prod['id']
                ]);
            }

            $db->commit(); // Si todo salió bien, guardamos cambios permanentes
            return true;

        } catch (Exception $e) {
            $db->rollBack(); // Si algo falló, deshacemos todo para proteger el inventario
            return false;
        }
    }
}