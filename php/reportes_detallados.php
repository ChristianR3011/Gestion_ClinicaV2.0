<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$desde = $_GET['desde'] ?? null;
$hasta = $_GET['hasta'] ?? null;
if (!$desde || !$hasta) {
    echo json_encode(['success'=>false,'error'=>'Fechas inválidas']);
    exit;
}

// 1) Obtengo todas las citas entre fechas, junto con especialidad, paciente y médico
$sql = "
  SELECT 
    e.nombre AS especialidad,
    CONCAT(p.nombres,' ',p.apellidos) AS paciente,
    CONCAT(m.nombres,' ',m.apellidos) AS medico,
    c.fecha_cita AS fecha,
    c.hora_cita AS hora,
    COALESCE(c.motivo_consulta,'') AS motivo
  FROM citas c
  JOIN medicos m      ON c.id_medico   = m.id_medico
  JOIN especialidades e ON m.id_especialidad = e.id_especialidad
  JOIN pacientes p    ON c.id_paciente = p.id_paciente
  WHERE c.fecha_cita BETWEEN ? AND ?
  ORDER BY e.nombre, c.fecha_cita, c.hora_cita
";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $desde, $hasta);
$stmt->execute();
$res = $stmt->get_result();

$grupos = [];
while ($row = $res->fetch_assoc()) {
    $esp = $row['especialidad'];
    if (!isset($grupos[$esp])) {
        $grupos[$esp] = [
            'especialidad' => $esp,
            'citas'        => []
        ];
    }
    $grupos[$esp]['citas'][] = [
        'paciente' => $row['paciente'],
        'medico'   => $row['medico'],
        'fecha'    => $row['fecha'],
        'hora'     => $row['hora'],
        'motivo'   => $row['motivo']
    ];
}
$stmt->close();
$conexion->close();

// Reindexar a array numérico
$salida = array_values($grupos);

echo json_encode(['success'=>true,'grupos'=>$salida]);

