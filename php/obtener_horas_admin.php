<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once 'conexion.php';

$id_medico = intval($_GET['id_medico'] ?? 0);
$fecha = $_GET['fecha'] ?? '';

if (!$id_medico || !$fecha) {
    echo json_encode([
        "success" => false,
        "error" => "Faltan parámetros"
    ]);
    exit;
}

//Obtendremos disponibilidad del médico ese día
$sql = "SELECT hora_inicio, hora_fin FROM disponibilidad_medica 
        WHERE id_medico = ? AND fecha = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("is", $id_medico, $fecha);
$stmt->execute();
$res = $stmt->get_result();

$horariosDisponibles = [];

while ($row = $res->fetch_assoc()) {
    $inicio = strtotime($row['hora_inicio']);
    $fin = strtotime($row['hora_fin']);

   
    while ($inicio < $fin) {
        $horaFormateada = date('H:i:s', $inicio);
        $horariosDisponibles[] = $horaFormateada;
        $inicio = strtotime("+30 minutes", $inicio);
    }
}

$stmt->close();

if (empty($horariosDisponibles)) {
    echo json_encode([
        "success" => true,
        "horas" => []
    ]);
    exit;
}

//Obtener horas ya ocupadas en citas
$sql2 = "SELECT hora_cita FROM citas 
         WHERE id_medico = ? AND fecha_cita = ?";
$stmt2 = $conexion->prepare($sql2);
$stmt2->bind_param("is", $id_medico, $fecha);
$stmt2->execute();
$res2 = $stmt2->get_result();

$horasOcupadas = [];
while ($row = $res2->fetch_assoc()) {
    $horasOcupadas[] = $row['hora_cita'];
}

$stmt2->close();

// 4. Filtrar las horas disponibles que NO estén ocupadas
$horasLibres = array_values(array_filter($horariosDisponibles, function($h) use ($horasOcupadas) {
    return !in_array($h, $horasOcupadas);
}));

sort($horasLibres);

echo json_encode([
    "success" => true,
    "horas" => $horasLibres
]);

$conexion->close();

