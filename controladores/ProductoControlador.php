<?php
require_once __DIR__ . "/../modelo/ProductoModelo.php";

class ProductoControlador extends ProductoModelo {

    public function listar_productos_controlador() {
        return parent::listar_productos_modelo();
    }

    public function paginador_productos_controlador($categoria = "General", $pagina = 1) {
        // Para la interfaz del comprador, devuelve HTML con tarjetas de productos
        try {
            $cat = trim($categoria);
            $catLower = mb_strtolower($cat, 'UTF-8');

            if ($catLower === 'ofertas') {
                $sql = parent::conectar()->prepare("SELECT * FROM productos ORDER BY stock DESC LIMIT 12");
            } elseif ($catLower === 'todas' || $catLower === 'general') {
                $sql = parent::conectar()->prepare("SELECT * FROM productos ORDER BY nombre ASC");
            } else {
                $sql = parent::conectar()->prepare("SELECT * FROM productos WHERE LOWER(TRIM(categoria)) = :cat ORDER BY nombre ASC");
                $sql->bindValue(":cat", $catLower, PDO::PARAM_STR);
            }
            $sql->execute();
            $productos = $sql->fetchAll(PDO::FETCH_ASSOC);

            $html = "";
            if (is_array($productos) && count($productos) > 0) {
                foreach ($productos as $prod) {
                    $stockDisponible = intval($prod['stock']);
                    $btnDisabled = ($stockDisponible <= 0) ? 'disabled' : '';
                    $textoBoton = ($stockDisponible <= 0) ? 'Agotado' : 'Agregar';

                    $html .= '
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm border-0 producto-card">
                            <div class="card-img-top p-2" style="height:150px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                <img src="vistas/imagenes/' . htmlspecialchars($prod['imagen']) . '" style="max-height: 140px; object-fit: contain;">
                            </div>
                            <div class="card-body">
                                <h6 class="card-title font-weight-bold">' . htmlspecialchars($prod['nombre']) . '</h6>
                                <p class="text-muted small mb-1">Código: ' . htmlspecialchars($prod['codigo_barras']) . '</p>
                                <p class="text-success font-weight-bold">$' . number_format($prod['precio_venta'], 2) . '</p>
                                <p class="badge badge-info">Stock: ' . $stockDisponible . '</p>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <button class="btn btn-primary btn-block btn-sm btn-agregar-carrito" 
                                        data-id="' . intval($prod['id']) . '" 
                                        data-nombre="' . htmlspecialchars($prod['nombre']) . '" 
                                        data-precio="' . number_format($prod['precio_venta'], 2) . '" 
                                        data-stock="' . $stockDisponible . '" ' . $btnDisabled . '>
                                    <i class="fas fa-cart-plus"></i> ' . $textoBoton . ' <span class="badge badge-light ml-1" id="count-prod-' . intval($prod['id']) . '">0</span>
                                </button>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                $html = '<div class="col-12 text-center"><p class="text-muted">No hay productos en esta categoría</p></div>';
            }
            return $html;
        } catch (Exception $e) {
            return '<div class="col-12"><div class="alert alert-danger">Error: ' . $e->getMessage() . '</div></div>';
        }
    }

    public function acciones_producto_controlador() {
        $id = isset($_POST['id_producto']) ? parent::limpiar_cadena($_POST['id_producto']) : 0;
        $codigo = parent::limpiar_cadena($_POST['codigo_barras']);
        $nombre = parent::limpiar_cadena($_POST['nombre']);
        $pc = isset($_POST['precio_compra']) ? parent::limpiar_cadena($_POST['precio_compra']) : "0.00";
        $pv = parent::limpiar_cadena($_POST['precio_venta']);
        $st = isset($_POST['stock']) ? parent::limpiar_cadena($_POST['stock']) : 0;
        $cat = isset($_POST['categoria']) ? parent::limpiar_cadena($_POST['categoria']) : "General";
        $modo = isset($_POST['modo_prod']) ? $_POST['modo_prod'] : "nuevo";

        $img = "default.png";

        if (isset($_FILES['foto_producto']) && $_FILES['foto_producto']['name'] != "") {
            $ext = strtolower(pathinfo($_FILES['foto_producto']['name'], PATHINFO_EXTENSION));
            $img = $codigo . "." . $ext;
            move_uploaded_file($_FILES['foto_producto']['tmp_name'], __DIR__ . "/../vistas/imagenes/" . $img);
        } elseif ($modo == "editar") {
            $prod = parent::obtener_producto_por_id($id)->fetch();
            $img = ($prod) ? $prod['imagen'] : "default.png";
        }

        $datos = [
            "id" => $id, 
            "codigo_barras" => $codigo, 
            "nombre" => $nombre,
            "precio_compra" => $pc, 
            "precio_venta" => $pv, 
            "stock" => $st, 
            "categoria" => $cat,
            "imagen" => $img
        ];

        $res = ($modo == "editar") ? parent::actualizar_producto_modelo($datos) : parent::guardar_producto_modelo($datos);
        $msj = ($modo == "editar") ? "Producto actualizado correctamente" : "Producto creado correctamente";
        return ["res" => $res ? "success" : "error", "msj" => $msj];
    }

    public function eliminar_producto_controlador($id) {
        $id = parent::limpiar_cadena($id);
        $res = parent::eliminar_producto_modelo($id);
        return ["res" => $res ? "success" : "error"];
    }
}