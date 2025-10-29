<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id_usuario = intval($_GET['id_usuario'] ?? 0);

if (!$id_usuario) {
    echo json_encode(['success' => false, 'error' => 'ID de usuario inválido']);
    exit;
}

// Buscamos al paciente relacionado con el usuario
$sql = "SELECT id_paciente FROM pacientes WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'id_paciente' => $row['id_paciente']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Paciente no encontrado']);
}

$stmt->close();
$conexion->close();
