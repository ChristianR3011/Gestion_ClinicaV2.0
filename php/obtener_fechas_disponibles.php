<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once 'conexion.php'; 

if (!isset($_GET['id_medico'])) {
    echo json_encode(["error" => "Falta el parámetro id_medico"]);
    exit;
}

$id_medico = intval($_GET['id_medico']);

$sql = "SELECT DISTINCT fecha FROM disponibilidad_medica WHERE id_medico = ? AND fecha >= CURDATE() ORDER BY fecha ASC";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en prepare(): " . $conexion->error]);
    exit;
}

$stmt->bind_param("i", $id_medico);
$stmt->execute();
$result = $stmt->get_result();

$fechas = [];
while ($row = $result->fetch_assoc()) {
    $fechas[] = $row['fecha'];
}

echo json_encode($fechas);
exit;

