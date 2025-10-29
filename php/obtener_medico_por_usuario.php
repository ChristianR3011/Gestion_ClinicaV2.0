<?php
header('Content-Type: application/json');
include 'conexion.php';

$id_usuario = $_GET['id_usuario'] ?? null;

if (!$id_usuario) {
    echo json_encode(['error' => 'ID de usuario requerido']);
    exit;
}

$sql = "SELECT id_medico, nombres, apellidos FROM medicos WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $medico = $result->fetch_assoc();
    echo json_encode($medico);
} else {
    echo json_encode(['error' => 'Médico no encontrado']);
}

$stmt->close();
$conexion->close();
?>
