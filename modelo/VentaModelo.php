<?php
require_once "../configuracion/conexiones.php";

class VentaModelo extends Conexion {

    /**
     * Busca un producto por código de barras o coincidencia en el nombre
     */
    public static function buscar_producto_modelo($busqueda) {
        $db = Conexion::conectar();
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
     * Registra la venta, los detalles y descuenta el stock
     * Nota: Se cambió a PUBLIC para permitir el acceso desde el controlador
     */
    public static function guardar_venta_modelo($datos) {
        $db = Conexion::conectar();
        
        try {
            // Iniciamos transacción para asegurar la integridad de los datos
            $db->beginTransaction(); 

            // 1. Insertar la cabecera de la venta
            // Usamos NOW() para la columna 'fecha' de tu base de datos
            $sqlVenta = $db->prepare("INSERT INTO ventas (fecha, total, usuario_id) 
                                     VALUES (NOW(), :total, :usuario)");
            $sqlVenta->bindParam(":total", $datos['total']);
            $sqlVenta->bindParam(":usuario", $datos['usuario_id']);
            $sqlVenta->execute();

            // Obtenemos el ID generado para esta venta
            $idVenta = $db->lastInsertId(); 

            // 2. Recorrer los productos para insertar detalles y restar inventario
            foreach ($datos['productos'] as $prod) {
                
                // Insertar en la tabla detalle_ventas
                $sqlDetalle = $db->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_venta) 
                                           VALUES (:v_id, :p_id, :cant, :precio)");
                $sqlDetalle->execute([
                    ":v_id"   => $idVenta,
                    ":p_id"   => $prod['id'],
                    ":cant"   => $prod['cantidad'],
                    ":precio" => $prod['precio_venta']
                ]);

                // Actualizar el stock restando la cantidad vendida
                $sqlStock = $db->prepare("UPDATE productos SET stock = stock - :cant WHERE id = :p_id");
                $sqlStock->execute([
                    ":cant" => $prod['cantidad'],
                    ":p_id" => $prod['id']
                ]);
            }

            // Si todo el ciclo terminó correctamente, confirmamos los cambios
            $db->commit(); 
            return true;

        } catch (Exception $e) {
            // Si ocurre cualquier error, revertimos todos los cambios (Rollback)
            $db->rollBack(); 
            return false;
        }
    }
}