<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id_cita = $_POST['id_cita'] ?? null;
$id_paciente = $_POST['paciente_id'] ?? null;
$id_medico = $_POST['medico_id'] ?? null;
$fecha = $_POST['fecha'] ?? null;
$hora = $_POST['hora'] ?? null;
$motivo = $_POST['motivo'] ?? null;
$estado = $_POST['estado'] ?? 'pendiente';

if (!$id_cita || !$id_paciente || !$id_medico || !$fecha || !$hora || !$motivo) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios.']);
    exit;
}

// Verificar si la cita existe
$sql_check = "SELECT id_cita FROM citas WHERE id_cita = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("i", $id_cita);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Cita no encontrada.']);
    exit;
}
$stmt_check->close();

// Verificar duplicados (mismo paciente/médico/fecha/hora, excluyendo la cita actual)
$sql_dup = "SELECT id_cita FROM citas WHERE id_paciente = ? AND id_medico = ? AND fecha_cita = ? AND hora_cita = ? AND id_cita != ?";
$stmt_dup = $conexion->prepare($sql_dup);
$stmt_dup->bind_param("iissi", $id_paciente, $id_medico, $fecha, $hora, $id_cita);
$stmt_dup->execute();
$result_dup = $stmt_dup->get_result();
if ($result_dup->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'Ya existe una cita con los mismos datos.']);
    exit;
}
$stmt_dup->close();

// Actualizar cita
$sql = "UPDATE citas SET id_paciente = ?, id_medico = ?, fecha_cita = ?, hora_cita = ?, motivo_consulta = ?, estado = ? WHERE id_cita = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iissssi", $id_paciente, $id_medico, $fecha, $hora, $motivo, $estado, $id_cita);

if ($stmt->execute()) {
    // Sincronizar motivo en historias_clinicas si cambió
    $sql_hist = "UPDATE historias_clinicas SET motivo_consulta = ? WHERE id_cita = ?";
    $stmt_hist = $conexion->prepare($sql_hist);
    $stmt_hist->bind_param("si", $motivo, $id_cita);
    $stmt_hist->execute();
    $stmt_hist->close();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar la cita.']);
}

$stmt->close();
$conexion->close();
