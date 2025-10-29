<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id_historia = $_GET['id'] ?? null;

if (!$id_historia) {
    echo json_encode(['success' => false, 'error' => 'ID de historia no válido']);
    exit;
}

$sql = "SELECT h.*, p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos,
               m.nombres AS medico_nombres, m.apellidos AS medico_apellidos
        FROM historias_clinicas h
        JOIN pacientes p ON h.id_paciente = p.id_paciente
        JOIN medicos m ON h.id_medico = m.id_medico
        WHERE h.id_historia = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_historia);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'historia' => $row]);
} else {
    echo json_encode(['success' => false, 'error' => 'Historia no encontrada']);
}

$stmt->close();
$conexion->close();
?>
