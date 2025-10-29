<?php
require_once "conexion.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id_especialidad"] ?? null;
$nombre = trim($data["nombre"] ?? "");

$response = ["success" => false];

if ($id && $nombre) {
    $stmt = $conexion->prepare("UPDATE especialidades SET nombre = ? WHERE id_especialidad = ?");
    $stmt->bind_param("si", $nombre, $id);

    if ($stmt->execute()) {
        $response["success"] = true;
    } else {
        $response["error"] = "Error SQL: " . $stmt->error;
    }

    $stmt->close();
} else {
    $response["error"] = "Datos inválidos.";
}

echo json_encode($response);
?>
