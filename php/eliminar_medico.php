<?php
require_once "conexion.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id_medico"] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "error" => "ID inválido"]);
    exit;
}

$stmt = $conexion->prepare("DELETE FROM medicos WHERE id_medico = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}
