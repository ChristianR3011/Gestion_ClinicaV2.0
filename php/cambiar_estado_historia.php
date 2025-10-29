<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No se recibió ningún JSON.']);
    exit;
}

$id_historia = $data['id_historia'] ?? null;
$estado = $data['estado'] ?? null;

if (!$id_historia || !in_array($estado, ['pendiente', 'atendida'])) {
    echo json_encode(['success' => false, 'error' => 'Parámetros inválidos']);
    exit;
}

$sql = "UPDATE historias_clinicas SET estado = ? WHERE id_historia = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("si", $estado, $id_historia);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al ejecutar actualización']);
}

$stmt->close();
$conexion->close();
