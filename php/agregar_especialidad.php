<?php
require_once "conexion.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$nombre = trim($data["nombre"] ?? "");

$response = ["success" => false];

if ($nombre === "") {
    $response["error"] = "El nombre está vacío.";
    echo json_encode($response);
    exit;
}

try {
    $stmt = $conexion->prepare("INSERT INTO especialidades (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);
    
    if ($stmt->execute()) {
        $response["success"] = true;
    } else {
        $response["error"] = "Error al insertar: " . $stmt->error;
    }

    $stmt->close();
} catch (Exception $e) {
    $response["error"] = "Excepción: " . $e->getMessage();
}

echo json_encode($response);
