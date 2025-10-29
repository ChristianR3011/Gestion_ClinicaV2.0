<?php
header('Content-Type: application/json');
include 'conexion.php';

$id_paciente = $_POST['id_paciente'] ?? null;
$fecha_registro = $_POST['fecha_registro'] ?? null;
$motivo_consulta = $_POST['motivo_consulta'] ?? null;
$diagnostico = $_POST['diagnostico'] ?? '';
$tratamiento = $_POST['tratamiento'] ?? '';
$id_medico = $_POST['id_medico'] ?? null;
$id_cita = $_POST['id_cita'] ?? null;

if (!$id_paciente || !$fecha_registro || !$motivo_consulta || !$id_medico) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios']);
    exit;
}

// Verificar que el paciente existe
$check_paciente = $conexion->prepare("SELECT id_paciente FROM pacientes WHERE id_paciente = ?");
$check_paciente->bind_param('i', $id_paciente);
$check_paciente->execute();
if ($check_paciente->get_result()->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Paciente no encontrado']);
    exit;
}
$check_paciente->close();

// Verificar que el médico existe
$check_medico = $conexion->prepare("SELECT id_medico FROM medicos WHERE id_medico = ?");
$check_medico->bind_param('i', $id_medico);
$check_medico->execute();
if ($check_medico->get_result()->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Médico no encontrado']);
    exit;
}
$check_medico->close();

// Verificar que la cita existe si se proporciona
if ($id_cita) {
    $check_cita = $conexion->prepare("SELECT id_cita FROM citas WHERE id_cita = ?");
    $check_cita->bind_param('i', $id_cita);
    $check_cita->execute();
    if ($check_cita->get_result()->num_rows == 0) {
        echo json_encode(['success' => false, 'error' => 'Cita no encontrada']);
        exit;
    }
    $check_cita->close();
}

$sql = "INSERT INTO historias_clinicas (id_cita, id_paciente, fecha_registro, motivo_consulta, diagnostico, tratamiento, id_medico)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('iissssi', $id_cita, $id_paciente, $fecha_registro, $motivo_consulta, $diagnostico, $tratamiento, $id_medico);

if ($stmt->execute()) {
    // Actualizar el estado de la cita a "atendido"
    if ($id_cita) {
        $update_sql = "UPDATE citas SET estado = 'atendido' WHERE id_cita = ?";
        $update_stmt = $conexion->prepare($update_sql);
        $update_stmt->bind_param('i', $id_cita);
        $update_stmt->execute();
        $update_stmt->close();
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al guardar: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
