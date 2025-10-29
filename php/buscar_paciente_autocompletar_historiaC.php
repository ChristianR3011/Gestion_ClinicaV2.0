<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$q = $_GET['q'] ?? '';

if (!$q) {
    $sql = "SELECT id_paciente, nombres, apellidos, dni FROM pacientes ORDER BY nombres";
    $stmt = $conexion->prepare($sql);
} else {
    $like = "%$q%";
    $sql = "SELECT id_paciente, nombres, apellidos, dni
            FROM pacientes
            WHERE nombres LIKE ? OR apellidos LIKE ? OR dni LIKE ?
            LIMIT 10";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $like, $like, $like);
}
$stmt->execute();
$res = $stmt->get_result();

$pacientes = [];
while ($row = $res->fetch_assoc()) {
    $pacientes[] = $row;
}

echo json_encode($pacientes);

$stmt->close();
$conexion->close();
