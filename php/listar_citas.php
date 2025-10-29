<?php
require 'conexion.php';

$sql = "SELECT c.id_cita, c.id_paciente,
               p.nombres, p.apellidos,
               m.nombres AS nom_doc, m.apellidos AS ape_doc,
               c.fecha_cita, c.hora_cita, c.estado, c.motivo_consulta AS motivo
        FROM citas c
        INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
        INNER JOIN medicos m ON c.id_medico = m.id_medico
        ORDER BY c.id_cita DESC";

$res = $conexion->query($sql);
$citas = [];

while ($row = $res->fetch_assoc()) {
  $citas[] = $row;
}

echo json_encode($citas);
?>
