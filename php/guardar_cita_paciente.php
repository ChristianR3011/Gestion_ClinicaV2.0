<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');


require_once 'conexion.php';

try {
 
  $conexion->begin_transaction();

 
  $id_paciente = $_POST['id_paciente'] ?? '';
  $id_medico      = $_POST['id_medico']      ?? '';
  $fecha_cita     = $_POST['fecha_cita']     ?? '';
  $hora_cita      = $_POST['hora_cita']      ?? '';
  $motivo         = $_POST['motivo_consulta']?? '';
  $diagnostico    = $_POST['diagnostico']    ?? '';
  $tratamiento    = ''; 
  $estado         = $_POST['estado']         ?? 'pendiente';

  // 4) Validar campos mínimos
  if (!$id_paciente || !$id_medico || !$fecha_cita || !$hora_cita || !$motivo) {
    throw new Exception('Faltan datos obligatorios');
  }

  // 5) Verificar existencia del paciente
 
$id_usuario = $id_paciente;
$verif_stmt = $conexion->prepare("SELECT id_paciente FROM pacientes WHERE id_usuario = ?");
$verif_stmt->bind_param("i", $id_usuario);
$verif_stmt->execute();
$result = $verif_stmt->get_result();

if ($result->num_rows === 0) {
  throw new Exception("El paciente no existe en la base de datos");
}

$row = $result->fetch_assoc();
$id_paciente = $row['id_paciente'];
$verif_stmt->close();

 
  $sql1 = "INSERT INTO citas (id_paciente, id_medico, fecha_cita, hora_cita, motivo_consulta, estado)
         VALUES (?, ?, ?, ?, ?, ?)";
$stmt1 = $conexion->prepare($sql1);
$stmt1->bind_param("iissss", $id_paciente, $id_medico, $fecha_cita, $hora_cita, $motivo, $estado);

  if (!$stmt1->execute()) {
    throw new Exception("Error al guardar en citas: " . $stmt1->error);
  }
  $id_cita = $stmt1->insert_id;
  $stmt1->close();

  
  $sql2 = "INSERT INTO historias_clinicas
           (id_paciente, fecha_registro, motivo_consulta, diagnostico, tratamiento, id_medico)
           VALUES (?, ?, ?, ?, ?, ?)";
  $stmt2 = $conexion->prepare($sql2);
  $stmt2->bind_param("issssi",
    $id_paciente,
    $fecha_cita,
    $motivo,
    $diagnostico,
    $tratamiento,
    $id_medico
  );
  if (!$stmt2->execute()) {
    throw new Exception("Error al guardar en historias: " . $stmt2->error);
  }
  $stmt2->close();

 
  $conexion->commit();
  echo json_encode(['success' => true]);

} catch (Exception $e) {
  
  if ($conexion && $conexion->errno) {
    $conexion->rollback();
  }
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
