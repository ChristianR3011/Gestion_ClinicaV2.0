<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id_cita = intval($_POST['id_cita'] ?? 0);

if (!$id_cita) {
    echo json_encode(['success' => false, 'error' => 'ID de cita inválido.']);
    exit;
}

// Verificar si la cita existe y no está ya cancelada
$sql_check = "SELECT estado FROM citas WHERE id_cita = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("i", $id_cita);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Cita no encontrada.']);
    exit;
}
$row = $result_check->fetch_assoc();
if ($row['estado'] === 'cancelada') {
    echo json_encode(['success' => false, 'error' => 'La cita ya está cancelada.']);
    exit;
}
$stmt_check->close();

// Actualizar estado a 'cancelada'
$sql = "UPDATE citas SET estado = 'cancelada' WHERE id_cita = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_cita);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al cancelar la cita.']);
}

$stmt->close();
$conexion->close();
