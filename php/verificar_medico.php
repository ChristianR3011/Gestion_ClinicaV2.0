<?php
header('Content-Type: application/json');
include 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_usuario = $data['id_usuario'] ?? null;

if (!$id_usuario) {
    echo json_encode(['is_medico' => false, 'error' => 'ID de usuario requerido']);
    exit;
}

$sql = "SELECT id_medico FROM medicos WHERE id_medico = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode(['is_medico' => $result->num_rows > 0]);

$stmt->close();
$conexion->close();
