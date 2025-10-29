<?php
require_once "conexion.php";

$id_especialidad = $_GET['id_especialidad'] ?? 0;
$busqueda = $_GET['busqueda'] ?? '';

$sql = "SELECT * FROM medicos WHERE id_especialidad = ? AND (nombres LIKE ? OR apellidos LIKE ?) ORDER BY id_medico";
$stmt = $conexion->prepare($sql);
$like = "%$busqueda%";
$stmt->bind_param("sss", $id_especialidad, $like, $like);
$stmt->execute();
$result = $stmt->get_result();

$medicos = [];
while ($row = $result->fetch_assoc()) {
  $medicos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($medicos);
?>
