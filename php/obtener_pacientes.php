<?php
include('conexion.php');
header('Content-Type: application/json');

$data = [];

try {
    $sql = "SELECT * FROM pacientes";
    $result = $conexion->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al obtener pacientes: " . $conexion->error]);
        exit;
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Excepción: " . $e->getMessage()]);
} finally {
    $conexion->close();
}
