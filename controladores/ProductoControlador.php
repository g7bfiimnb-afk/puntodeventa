<?php
// Validamos si la petición viene de un archivo en una subcarpeta (Ajax) o de la raíz (Index)
if(file_exists("../modelo/ProductoModelo.php")){
    require_once "../modelo/ProductoModelo.php";
} else {
    require_once "./modelo/ProductoModelo.php";
}

class ProductoControlador extends ProductoModelo {

    public function agregar_producto_controlador() {
        // Limpiamos los datos
        $codigo = trim($_POST['codigo_barra']);
        $nombre = trim($_POST['nombre_prod']);
        $p_compra = $_POST['p_compra'];
        $p_venta = $_POST['p_venta'];
        $stock = $_POST['stock'];

        // Validación simple
        if($codigo == "" || $nombre == "" || $p_venta <= 0){
            return json_encode([
                "res" => "error",
                "msj" => "El código, nombre y precio de venta son obligatorios"
            ]);
        }

        $datosProd = [
            "codigo" => $codigo,
            "nombre" => $nombre,
            "p_compra" => $p_compra,
            "p_venta" => $p_venta,
            "stock" => $stock
        ];

        $guardar = parent::agregar_producto_modelo($datosProd);

        if($guardar) {
            return json_encode([
                "res" => "success",
                "msj" => "Producto registrado correctamente"
            ]);
        } else {
            return json_encode([
                "res" => "error",
                "msj" => "Error al guardar (Es posible que el código ya exista)"
            ]);
        }
    }

    // Añade esto dentro de la clase ProductoControlador
    public function listar_productos_controlador() {
        $productos = parent::consultar_productos_modelo();
        return $productos; // Retorna el array de productos
    }

   // Añade esto dentro de la clase ProductoControlador
    public function eliminar_producto_controlador($id) {
        $id = intval($id); // Aseguramos que sea un número
        
        $eliminar = parent::eliminar_producto_modelo($id);
        
        if($eliminar) {
            return json_encode(["res" => "success", "msj" => "Producto eliminado"]);
        } else {
            return json_encode(["res" => "error", "msj" => "No se pudo eliminar el producto"]);
        }
    }

    public function guardar_producto_controlador() {
        $codigo = $_POST['producto_codigo'];
        $nombre = $_POST['producto_nombre'];
        $precio = $_POST['producto_precio'];
        $stock = $_POST['producto_stock'];
        $categoria = $_POST['producto_categoria']; // Capturamos la categoría

        $datos = [
            "codigo" => $codigo,
            "nombre" => $nombre,
            "precio" => $precio,
            "stock" => $stock,
            "categoria" => $categoria
        ];

        return ProductoModelo::guardar_producto_modelo($datos);
    }
}