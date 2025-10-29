<?php
require_once 'conexion.php';

$fecha = $_GET['fecha'] ?? '';

if (!$fecha) {
    echo json_encode(['success' => false, 'error' => 'Fecha no proporcionada']);
    exit;
}

$sql = "SELECT c.id_cita, c.fecha_cita, c.hora_cita, 
               COALESCE(h.motivo_consulta, '') AS motivo_consulta, 
               c.estado,
               p.nombres AS nombre_paciente,
               m.nombres AS nombre_medico
        FROM citas c
        JOIN pacientes p ON c.id_paciente = p.id_paciente
        JOIN medicos m ON c.id_medico = m.id_medico
        LEFT JOIN (
            SELECT * FROM historias_clinicas 
            ORDER BY id_historia DESC
        ) h ON h.id_paciente = c.id_paciente 
            AND h.id_medico = c.id_medico 
            AND h.fecha_registro = c.fecha_cita
        WHERE c.fecha_cita = ?
        GROUP BY c.id_cita
        ORDER BY c.hora_cita ASC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $fecha);
$stmt->execute();
$res = $stmt->get_result();

$citas = [];
while ($row = $res->fetch_assoc()) {
    $citas[] = $row;
}

echo json_encode([
    'success' => true,
    'citas' => $citas
]);

$stmt->close();
$conexion->close();
