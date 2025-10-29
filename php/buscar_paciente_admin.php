<?php
require_once 'conexion.php';
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';
if (!$q) {
  echo json_encode([]);
  exit;
}

// Búsqueda parcial por nombre completo (nombre + apellido)
$sql = "SELECT id_paciente, nombres, apellidos FROM pacientes WHERE CONCAT(nombres, ' ', apellidos) LIKE ? LIMIT 10";
$stmt = $conexion->prepare($sql);
$like = "%$q%";
$stmt->bind_param("s", $like);
$stmt->execute();
$res = $stmt->get_result();

$pacientes = [];
while ($row = $res->fetch_assoc()) {
  $pacientes[] = [
    "id_paciente" => $row['id_paciente'],
    "nombres" => $row['nombres'],
    "apellidos" => $row['apellidos'],
    "nombre_completo" => $row['nombres'] . ' ' . $row['apellidos']
  ];
}

echo json_encode($pacientes);

$stmt->close();
$conexion->close();
