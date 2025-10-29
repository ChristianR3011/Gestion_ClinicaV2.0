<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$sql = "SELECT id_medico, CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM medicos ORDER BY nombres";
$result = $conexion->query($sql);

$medicos = [];
while ($row = $result->fetch_assoc()) {
    $medicos[] = $row;
}

echo json_encode($medicos);

$result->close();
$conexion->close();
?>
