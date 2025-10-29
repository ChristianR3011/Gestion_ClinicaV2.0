<?php

header('Content-Type: application/json');
require_once 'conexion.php';

$id_cita = intval($_POST['id_cita'] ?? 0);

if (!$id_cita) {
  echo json_encode(["success" => false, "error" => "ID inválido"]);
  exit;
}

$sql = "UPDATE citas SET estado = 'atendida' WHERE id_cita = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_cita);
$ok = $stmt->execute();

if ($ok) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "error" => "No se pudo actualizar"]);
}

$stmt->close();
$conexion->close();

