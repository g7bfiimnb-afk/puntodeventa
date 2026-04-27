<?php
if(isset($peticionAjax) && $peticionAjax){
    require_once "../modelo/ProductoModelo.php";
    require_once "../modelo/VentaModelo.php";
}else{
    require_once "./modelo/ProductoModelo.php";
    if(file_exists("./modelo/VentaModelo.php")){
        require_once "./modelo/VentaModelo.php";
    }
}

class VentaControlador extends ProductoModelo {

    /**
     * Busca un producto por código de barras O por nombre
     */
    public function buscar_producto_venta_controlador($codigo) {
        $codigo = trim($codigo);
        if($codigo == "") return json_encode(["res" => "error", "msj" => "Ingrese un código o nombre"]);

        $db = Conexion::conectar();
        
        // Mejoramos la consulta para que acepte nombre también, así funciona tu lupa con "huevos"
        $sql = $db->prepare("SELECT id, nombre, precio_venta, stock FROM productos 
                             WHERE codigo_barras = :codigo 
                             OR nombre LIKE CONCAT('%', :codigo, '%') LIMIT 1");
        
        $sql->bindParam(":codigo", $codigo);
        $sql->execute();
        $producto = $sql->fetch(PDO::FETCH_ASSOC);

        if($producto) {
            if($producto['stock'] > 0) {
                return json_encode([
                    "res" => "success",
                    "data" => $producto
                ]);
            } else {
                return json_encode([
                    "res" => "error", 
                    "msj" => "El producto '{$producto['nombre']}' no tiene stock disponible."
                ]);
            }
        } else {
            return json_encode(["res" => "error", "msj" => "No se encontró el producto."]);
        }
    }

    public function guardar_venta_controlador() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        // Si la sesión se cerró, avisamos al JS en lugar de mandar el HTML del login
        if(!isset($_SESSION['usuario_id'])){
            return json_encode(["res" => "error", "msj" => "Sesión expirada. Reinicie el sistema."]);
        }

        // Decodificar el JSON que viene desde JavaScript
        $productos = (isset($_POST['productos_venta'])) ? json_decode($_POST['productos_venta'], true) : [];
        $total = (isset($_POST['total_venta'])) ? floatval($_POST['total_venta']) : 0;
        $usuario = $_SESSION['usuario_id'];
        
        // Información del cliente
        $cliente_nombre = (isset($_POST['cliente_nombre'])) ? trim($_POST['cliente_nombre']) : '';
        $cliente_id_usuario = (isset($_POST['cliente_id_usuario'])) ? trim($_POST['cliente_id_usuario']) : '';
        $cliente_nombre_elegido = (isset($_POST['cliente_nombre_elegido'])) ? trim($_POST['cliente_nombre_elegido']) : '';
        $cliente_ocupacion = (isset($_POST['cliente_ocupacion'])) ? trim($_POST['cliente_ocupacion']) : '';
        $cliente_email = (isset($_POST['cliente_email'])) ? trim($_POST['cliente_email']) : '';
        $cliente_telefono = (isset($_POST['cliente_telefono'])) ? trim($_POST['cliente_telefono']) : '';
        $cliente_rfc = (isset($_POST['cliente_rfc'])) ? trim($_POST['cliente_rfc']) : '';
        $cliente_situacion_fiscal = (isset($_POST['cliente_situacion_fiscal'])) ? trim($_POST['cliente_situacion_fiscal']) : '';
        $cliente_direccion = (isset($_POST['cliente_direccion'])) ? trim($_POST['cliente_direccion']) : '';
        $cliente_referencias = (isset($_POST['cliente_referencias'])) ? trim($_POST['cliente_referencias']) : '';
        $metodo_pago = (isset($_POST['metodo_pago'])) ? trim($_POST['metodo_pago']) : 'efectivo';

        if(empty($productos)){
            return json_encode(["res" => "error", "msj" => "El carrito está vacío."]);
        }

        $datosVenta = [
            "total" => $total,
            "usuario_id" => $usuario,
            "productos" => $productos,
            "cliente_nombre" => $cliente_nombre,
            "cliente_id_usuario" => $cliente_id_usuario,
            "cliente_nombre_elegido" => $cliente_nombre_elegido,
            "cliente_ocupacion" => $cliente_ocupacion,
            "cliente_email" => $cliente_email,
            "cliente_telefono" => $cliente_telefono,
            "cliente_rfc" => $cliente_rfc,
            "cliente_situacion_fiscal" => $cliente_situacion_fiscal,
            "cliente_direccion" => $cliente_direccion,
            "cliente_referencias" => $cliente_referencias,
            "metodo_pago" => $metodo_pago
        ];

        $res = VentaModelo::guardar_venta_modelo($datosVenta);

        if($res) {
            return json_encode([
                "res" => "success", 
                "msj" => "Venta guardada con éxito."
            ]);
        } else {
            return json_encode([
                "res" => "error", 
                "msj" => "Error al guardar en la base de datos."
            ]);
        }
    }

        // Dentro de la clase VentaControlador
    public function obtener_total_ventas_hoy() {
        require_once "./modelo/VentaModelo.php";
        $total = VentaModelo::sumar_ventas_hoy_modelo();
        return number_format($total, 2);
    }
}