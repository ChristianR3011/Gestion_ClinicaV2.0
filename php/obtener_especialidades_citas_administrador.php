<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$sql = "SELECT id_especialidad, nombre FROM especialidades";
$result = $conexion->query($sql);

$especialidades = [];
while ($row = $result->fetch_assoc()) {
    $especialidades[] = $row;
}

echo json_encode($especialidades);
$conexion->close();
