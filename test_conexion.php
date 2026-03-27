<?php
// Archivo de prueba para verificar la conexión
header('Content-Type: application/json');

try {
    $link = new PDO("mysql:host=127.0.0.1;dbname=puntodeventa;charset=utf8mb4", "root", "");
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo json_encode(["status" => "success", "mensaje" => "Conexión exitosa"]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error", 
        "mensaje" => $e->getMessage(),
        "código" => $e->getCode()
    ]);
}
exit;
