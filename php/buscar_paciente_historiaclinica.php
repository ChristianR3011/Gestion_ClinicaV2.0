<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id_paciente = $_GET['id_paciente'] ?? null;
$desde       = $_GET['desde']       ?? null;
$hasta       = $_GET['hasta']       ?? null;

if (!$id_paciente) {
    echo json_encode(['success' => false, 'error' => 'ID no válido']);
    exit;
}


$stmt = $conexion->prepare("
    SELECT nombres, apellidos, correo, telefono, fecha_nacimiento, direccion
    FROM pacientes
    WHERE id_paciente = ?
");
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$res = $stmt->get_result();
$paciente = $res->fetch_assoc();
$stmt->close();

if (!$paciente) {
    echo json_encode(['success' => false, 'error' => 'Paciente no encontrado']);
    exit;
}


$sql = "
    SELECT
        h.id_historia,
        h.id_cita,
        COALESCE(c.fecha_cita, h.fecha_registro) AS fecha_cita,
        h.motivo_consulta,
        h.diagnostico,
        h.tratamiento,
        COALESCE(c.estado, 'sin cita') AS estado,
        CONCAT(m.nombres,' ',m.apellidos) AS nombre_medico
    FROM historias_clinicas h
    LEFT JOIN citas c    ON h.id_cita   = c.id_cita
    JOIN medicos m  ON h.id_medico = m.id_medico
    WHERE h.id_paciente = ?
";
$tipos  = "i";
$params = [$id_paciente];

if ($desde && $hasta) {
    $sql .= " AND c.fecha_cita BETWEEN ? AND ?";
    $tipos  .= "ss";
    $params[] = $desde;
    $params[] = $hasta;
}

$sql .= " ORDER BY c.fecha_cita DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param($tipos, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$historias = [];
while ($row = $res->fetch_assoc()) {
    $historias[] = [
        'id_historia'     => (int)$row['id_historia'],
        'id_cita'         => (int)$row['id_cita'],
        'fecha_cita'      => $row['fecha_cita'],
        'motivo_consulta' => $row['motivo_consulta'],
        'diagnostico'     => $row['diagnostico'],
        'tratamiento'     => $row['tratamiento'],
        'estado'          => $row['estado'],
        'nombre_medico'   => $row['nombre_medico']
    ];
}

$stmt->close();
$conexion->close();

echo json_encode([
    'success'   => true,
    'paciente'  => $paciente,
    'historias' => $historias
]);
