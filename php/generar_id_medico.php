<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "conexion.php";

$id_especialidad = $_GET['id_especialidad'] ?? '';
if (!$id_especialidad) {
    echo json_encode(['success' => false, 'error' => 'ID de especialidad requerido']);
    exit;
}


$stmt = $conn->prepare("SELECT nombre FROM especialidades WHERE id_especialidad = ?");
$stmt->bind_param("i", $id_especialidad);
$stmt->execute();
$result = $stmt->get_result();
$especialidad = $result->fetch_assoc();

if (!$especialidad) {
    echo json_encode(['success' => false, 'error' => 'Especialidad no encontrada']);
    exit;
}


$nombre = preg_replace("/[^a-zA-Z]/", "", $especialidad['nombre']); 
$prefijo = strtoupper(substr($nombre, 0, 5)); 


$stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM medicos WHERE id_medico LIKE CONCAT(?, '%')");
$stmt2->bind_param("s", $prefijo);
$stmt2->execute();
$result2 = $stmt2->get_result();
$row = $result2->fetch_assoc();

$total = $row['total'] + 1;
$id_medico = $prefijo . str_pad($total, 2, '0', STR_PAD_LEFT);


echo json_encode(['success' => true, 'id_medico' => $id_medico]);
exit;

