<?php
session_start();
header('Content-Type: application/json');
require_once 'conexion.php';

// Verifica si el paciente está autenticado
$id_paciente = $_SESSION['id_paciente'] ?? null;

if (!$id_paciente) {
    echo json_encode(['error' => 'Paciente no autenticado']);
    exit;
}

// Parámetros opcionales para filtrar por rango de fechas
$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;

// Obtener datos del paciente (opcional)
$sqlPaciente = "SELECT * FROM pacientes WHERE id_paciente = ?";
$stmtPaciente = $conexion->prepare($sqlPaciente);
$stmtPaciente->bind_param("i", $id_paciente);
$stmtPaciente->execute();
$resPaciente = $stmtPaciente->get_result();
$paciente = $resPaciente->fetch_assoc();

// Obtener historial clínico
$sqlHistorial = "SELECT hc.*, m.nombres AS nombre_medico, m.apellidos AS apellido_medico
                 FROM historias_clinicas hc
                 JOIN medicos m ON hc.id_medico = m.id_medico
                 WHERE hc.id_paciente = ?";

$params = [$id_paciente];
$types = "i";

// Si se seleccionó un rango de fechas
if ($fecha_inicio && $fecha_fin) {
    $sqlHistorial .= " AND hc.fecha_registro BETWEEN ? AND ?";
    $types .= "ss";
    $params[] = $fecha_inicio;
    $params[] = $fecha_fin;
}

$sqlHistorial .= " ORDER BY hc.fecha_registro DESC";

$stmtHistorial = $conexion->prepare($sqlHistorial);
$stmtHistorial->bind_param($types, ...$params);
$stmtHistorial->execute();
$resHistorial = $stmtHistorial->get_result();

$historial = [];
while ($row = $resHistorial->fetch_assoc()) {
    $historial[] = $row;
}

// Respuesta JSON
echo json_encode([
    'paciente' => $paciente,
    'historial' => $historial
]);

