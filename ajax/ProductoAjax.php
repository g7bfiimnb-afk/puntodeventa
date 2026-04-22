<?php
require_once "../configuracion/conexiones.php";

header('Content-Type: application/json'); // Asegurar respuesta JSON limpia

// ACCIÓN: TRAER DATOS PARA EDITAR
if(isset($_POST['id_traer'])){
    $db = Conexion::conectar();
    $sql = $db->prepare("SELECT * FROM productos WHERE id = ?");
    $sql->execute([$_POST['id_traer']]);
    $datos = $sql->fetch(PDO::FETCH_ASSOC);
    echo json_encode($datos);
    exit();
}

// ACCIÓN: GUARDAR (NUEVO O EDITAR) CON FOTO
if(isset($_POST['codigo_prod'])){
    $db = Conexion::conectar();
    
    // Recibir datos normales
    $id = $_POST['id_producto']; // Puede venir vacío si es nuevo
    $codigo = $_POST['codigo_prod'];
    $nombre = $_POST['nombre_prod'];
    $p_compra = $_POST['p_compra_prod'];
    $p_venta = $_POST['p_venta_prod'];
    $stock = $_POST['stock_prod'];
    
    $nombre_foto_bd = 'default.png'; // Por defecto

    // --- LÓGICA DE SUBIDA DE FOTO ---
    if(isset($_FILES['foto_prod']) && $_FILES['foto_prod']['error'] == 0){
        
        $tipo = $_FILES['foto_prod']['type'];
        $tamano = $_FILES['foto_prod']['size'];
        $temporal = $_FILES['foto_prod']['tmp_name'];

        // Validar tipo (jpg, png, webp, avif)
        $tipos_permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/avif'];
        if(in_array($tipo, $tipos_permitidos)){
            // Validar tamaño (2MB)
            if($tamano < 2000000){
                
                // Renombrar foto para evitar duplicados (ej: 105_sabritas.jpg)
                $ext = pathinfo($_FILES['foto_prod']['name'], PATHINFO_EXTENSION);
                $nombre_foto_bd = $codigo . "_" . time() . "." . $ext;
                
                $ruta_destino = "../vistas/img/productos/" . $nombre_foto_bd;

                if(!move_uploaded_file($temporal, $ruta_destino)){
                    echo json_encode(["res" => "error", "msj" => "No se pudo mover la foto al servidor."]);
                    exit();
                }

                // SI ES EDITAR, deberías borrar la foto vieja aquí (opcional)

            } else {
                echo json_encode(["res" => "error", "msj" => "La foto es muy grande. Max 2MB."]);
                exit();
            }
        } else {
            echo json_encode(["res" => "error", "msj" => "Formato de foto no válido. Solo JPG/PNG/WEBP/AVIF."]);
            exit();
        }
    } else {
        // Si es editar y no subió foto nueva, debemos mantener la foto que ya tenía
        if(!empty($id)){
            $sql_foto = $db->prepare("SELECT imagen FROM productos WHERE id = ?");
            $sql_foto->execute([$id]);
            $res_foto = $sql_foto->fetch();
            $nombre_foto_bd = $res_foto['imagen'];
        }
    }


    // --- LÓGICA DE BASE DE DATOS (INSERT O UPDATE) ---
    if(empty($id)){
        // ES NUEVO
        $sql = $db->prepare("INSERT INTO productos (codigo_barras, nombre, precio_compra, precio_venta, stock, imagen) VALUES (?,?,?,?,?,?)");
        if($sql->execute([$codigo, $nombre, $p_compra, $p_venta, $stock, $nombre_foto_bd])){
            echo json_encode(["res" => "success", "msj" => "¡Producto creado con éxito!"]);
        } else {
            echo json_encode(["res" => "error", "msj" => "Error al guardar en BD."]);
        }
    } else {
        // ES EDITAR
        $sql = $db->prepare("UPDATE productos SET codigo_barras=?, nombre=?, precio_compra=?, precio_venta=?, stock=?, imagen=? WHERE id=?");
        if($sql->execute([$codigo, $nombre, $p_compra, $p_venta, $stock, $nombre_foto_bd, $id])){
            echo json_encode(["res" => "success", "msj" => "¡Producto actualizado con éxito!"]);
        } else {
            echo json_encode(["res" => "error", "msj" => "Error al actualizar en BD."]);
        }
    }
    exit();
}