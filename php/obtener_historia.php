<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$id_paciente = $_GET['id_paciente'] ?? null;

if (!$id_paciente) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT h.fecha_registro, h.motivo_consulta, h.diagnostico, h.tratamiento,
               m.nombres AS medico_nombre, m.apellidos AS medico_apellido
        FROM historias_clinicas h
        JOIN medicos m ON h.id_medico = m.id_medico
        WHERE h.id_paciente = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$resultado = $stmt->get_result();

$eventos = [];

while ($fila = $resultado->fetch_assoc()) {
    $eventos[] = [
        'fecha_registro' => $fila['fecha_registro'],
        'motivo_consulta' => $fila['motivo_consulta'],
        'diagnostico' => $fila['diagnostico'],
        'tratamiento' => $fila['tratamiento'],
        'medico' => $fila['medico_nombre'] . ' ' . $fila['medico_apellido']
    ];
}

echo json_encode($eventos);
?>
