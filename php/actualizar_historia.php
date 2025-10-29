<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id_historia   = $_POST['id_historia'] ?? '';
$diagnostico   = $_POST['diagnostico'] ?? '';
$tratamiento   = $_POST['tratamiento'] ?? '';

if (!$id_historia) {
    echo json_encode(['success' => false, 'error' => 'ID de historia no válido.']);
    exit;
}

$sql = "UPDATE historias_clinicas 
        SET diagnostico = ?, tratamiento = ?
        WHERE id_historia = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssi", $diagnostico, $tratamiento, $id_historia);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar la historia.']);
}

$stmt->close();
$conexion->close();
