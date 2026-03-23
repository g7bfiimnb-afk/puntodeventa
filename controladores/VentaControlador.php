<?php

if($peticionAjax){
    require_once "../modelo/VentaModelo.php";
}else{
    require_once "./modelo/VentaModelo.php";
}

class VentaControlador extends VentaModelo {

    /**
     * Controlador para buscar producto y agregarlo a la venta
     */
    public function buscar_producto_controlador($codigo) {
        
        // Limpiamos el código para evitar carácteres extraños
        $codigo = trim($codigo);
        
        if($codigo == ""){
            return json_encode(["res" => "error", "msj" => "El código está vacío"]);
        }

        // Llamamos al modelo (heredado de VentaModelo)
        $respuesta = parent::buscar_producto_modelo($codigo);

        if($respuesta) {
            // Si el producto existe, lo devolvemos como JSON
            return json_encode([
                "res" => "success",
                "datos" => $respuesta
            ]);
        } else {
            // Si no existe o no hay stock
            return json_encode([
                "res" => "error", 
                "msj" => "Producto no encontrado o sin existencias"
            ]);
        }
    }

    /**
     * Generador de folios para la tiendita (Ej: V-0001)
     */
    public function generar_folio_controlador() {
        // Aquí podrías consultar la última venta y sumarle 1
        return "V-" . date("is"); 
    }
}