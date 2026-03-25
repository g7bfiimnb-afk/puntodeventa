<?php
require_once "../configuracion/conexiones.php";

class ReporteModelo extends Conexion {

    // Consulta para ventas según el periodo (dia, semana, mes, año)
    public static function obtener_reporte_ventas($periodo) {
        $db = Conexion::conectar();
        $condicion = self::definir_periodo_sql($periodo);
        
        $sql = $db->prepare("SELECT id, fecha, total, usuario_id 
                             FROM ventas 
                             WHERE $condicion 
                             ORDER BY fecha DESC");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Consulta para productos agregados según el periodo
    public static function obtener_reporte_productos($periodo) {
        $db = Conexion::conectar();
        $condicion = self::definir_periodo_sql($periodo);
        
        // Asumiendo que tienes una columna 'fecha_registro' en tu tabla productos
        $sql = $db->prepare("SELECT codigo_barras, nombre, precio_venta, stock, categoria 
                             FROM productos 
                             WHERE $condicion 
                             ORDER BY id DESC");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función auxiliar para manejar los tiempos de SQL
    private static function definir_periodo_sql($p) {
        switch ($p) {
            case 'dia':    return "DATE(fecha) = CURDATE()";
            case 'semana': return "YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)";
            case 'mes':    return "MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())";
            case 'anio':   return "YEAR(fecha) = YEAR(CURDATE())";
            default:       return "DATE(fecha) = CURDATE()";
        }
    }
}