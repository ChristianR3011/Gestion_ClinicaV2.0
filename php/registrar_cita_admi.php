<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id_paciente = $_POST['paciente_id'] ?? null;
$id_medico   = $_POST['medico_id'] ?? null;
$fecha       = $_POST['fecha'] ?? null;
$hora        = $_POST['hora'] ?? null;
$motivo      = $_POST['motivo'] ?? null;
$estado      = $_POST['estado'] ?? 'pendiente';

if (!$id_paciente || !$id_medico || !$fecha || !$hora || !$motivo) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios.']);
    exit;
}


$sql = "INSERT INTO citas (id_paciente, id_medico, fecha_cita, hora_cita, motivo_consulta, estado)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iissss", $id_paciente, $id_medico, $fecha, $hora, $motivo, $estado);

if ($stmt->execute()) {
    $id_cita = $conexion->insert_id;

    //Insertar historia clínica vacía
    $sql_historia = "INSERT INTO historias_clinicas 
        (id_cita, id_paciente, fecha_registro, motivo_consulta, diagnostico, tratamiento, id_medico) 
        VALUES (?, ?, ?, ?, '', '', ?)";

    $fecha_actual = date('Y-m-d');
    $stmt_hist = $conexion->prepare($sql_historia);
    $stmt_hist->bind_param("iissi", $id_cita, $id_paciente, $fecha_actual, $motivo, $id_medico);
    $stmt_hist->execute();
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al registrar la cita.']);
}

$stmt->close();
$conexion->close();

