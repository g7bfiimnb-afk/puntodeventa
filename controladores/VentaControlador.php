<?php
if(isset($peticionAjax) && $peticionAjax){
    require_once "../modelo/ProductoModelo.php";
    // require_once "../modelo/VentaModelo.php"; // Lo activaremos al cobrar
}else{
    require_once "./modelo/ProductoModelo.php";
}

class VentaControlador extends ProductoModelo {

    /**
     * Busca un producto por código de barras para añadirlo al carrito
     */
    public function buscar_producto_venta_controlador($codigo) {
        $codigo = trim($codigo);

        // Usamos una consulta simple para traer el producto
        $db = Conexion::conectar();
        $sql = $db->prepare("SELECT id, nombre, precio_venta, stock FROM productos WHERE codigo_barras = :codigo LIMIT 1");
        $sql->bindParam(":codigo", $codigo);
        $sql->execute();
        
        $producto = $sql->fetch(PDO::FETCH_ASSOC);

        if($producto) {
            // Verificamos si hay stock disponible
            if($producto['stock'] > 0) {
                return json_encode([
                    "res" => "success",
                    "data" => $producto
                ]);
            } else {
                return json_encode([
                    "res" => "error",
                    "msj" => "Producto sin inventario (Stock: 0)"
                ]);
            }
        } else {
            return json_encode([
                "res" => "error",
                "msj" => "Producto no encontrado"
            ]);
        }
    }
}