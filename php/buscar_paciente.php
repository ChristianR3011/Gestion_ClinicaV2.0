<?php
include('conexion.php');
header('Content-Type: application/json');

$termino = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : '';

if ($termino === '') {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id_paciente AS id, CONCAT(nombres, ' ', apellidos) AS nombre
        FROM pacientes
        WHERE nombres LIKE '%$termino%' OR apellidos LIKE '%$termino%' OR dni LIKE '%$termino%'";

$result = $conexion->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'label' => $row['nombre'],
        'value' => $row['nombre']
    ];
}

echo json_encode($data);
?>
