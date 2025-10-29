<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id_historia'] ?? null;
$motivo = $data['motivo'] ?? '';
$diagnostico = $data['diagnostico'] ?? '';
$tratamiento = $data['tratamiento'] ?? '';

if (!$id || !$motivo) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos']);
    exit;
}

$sql = "UPDATE historias_clinicas SET motivo_consulta = ?, diagnostico = ?, tratamiento = ? WHERE id_historia = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssi", $motivo, $diagnostico, $tratamiento, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar']);
}

$stmt->close();
$conexion->close();
