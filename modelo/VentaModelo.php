<?php
// Esto busca el archivo conexiones.php de forma infalible
require_once __DIR__ . '/../configuracion/conexiones.php';

class VentaModelo extends Conexion {
    protected static function obtener_productos_modelo($inicio) {
        $db = Conexion::conectar();
        if(!$db) return false;
        
        // Consultamos la tabla 'productos'
        $sql = $db->prepare("SELECT * FROM productos LIMIT :inicio, 12");
        $sql->bindValue(":inicio", (int)$inicio, PDO::PARAM_INT);
        $sql->execute();
        return $sql;
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
                // Verificar que exista el producto y hay stock suficiente
                $sqlCheck = $db->prepare("SELECT stock, precio_venta FROM productos WHERE id = :p_id FOR UPDATE");
                $sqlCheck->execute([":p_id" => $prod['id']]);
                $row = $sqlCheck->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    throw new Exception("El producto con ID {$prod['id']} no existe.");
                }

                $stockActual = intval($row['stock']);
                $cantidadVendida = intval($prod['cantidad']);

                if ($cantidadVendida <= 0) {
                    throw new Exception("Cantidad inválida para el producto ID {$prod['id']}.");
                }

                if ($cantidadVendida > $stockActual) {
                    throw new Exception("No hay stock suficiente para el producto ID {$prod['id']}. Stock disponible: {$stockActual}.");
                }

                // Insertar en la tabla detalle_ventas
                $precioDetalle = isset($prod['precio']) ? $prod['precio'] : $row['precio_venta'];
                $sqlDetalle = $db->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_venta) 
                                           VALUES (:v_id, :p_id, :cant, :precio)");
                $sqlDetalle->execute([
                    ":v_id"   => $idVenta,
                    ":p_id"   => $prod['id'],
                    ":cant"   => $cantidadVendida,
                    ":precio" => $precioDetalle
                ]);

                // Actualizar el stock restando la cantidad vendida
                $sqlStock = $db->prepare("UPDATE productos SET stock = stock - :cant WHERE id = :p_id");
                $sqlStock->execute([
                    ":cant" => $cantidadVendida,
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

    // Dentro de la clase VentaModelo
    public static function sumar_ventas_hoy_modelo() {
        $db = Conexion::conectar();
        // Sumamos la columna 'total' filtrando por la fecha actual
        $sql = $db->prepare("SELECT SUM(total) as total_hoy FROM ventas WHERE DATE(fecha) = CURDATE()");
        $sql->execute();
        $res = $sql->fetch(PDO::FETCH_ASSOC);
        
        // Si no hay ventas, devolvemos 0 para evitar errores
        return ($res['total_hoy'] != "") ? $res['total_hoy'] : 0;
    }

}