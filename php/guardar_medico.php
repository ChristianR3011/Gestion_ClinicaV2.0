<?php
require_once "conexion.php";
session_start();


if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => '⛔ Acceso denegado. Debes iniciar sesión como administrador.']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_medico = $_POST['id_medico'] ?? '';
$nombres = $_POST['nombres'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$cmp = $_POST['cmp'] ?? '';
$id_especialidad = $_POST['id_especialidad'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$correo = $_POST['correo'] ?? '';
$direccion = $_POST['direccion'] ?? '';


if (!$nombres || !$apellidos || !$cmp || !$id_especialidad || !$telefono || !$correo) {
    echo json_encode(['success' => false, 'error' => '❗ Todos los campos son obligatorios']);
    exit;
}

if ($id_medico) {
   
    $stmt = $conexion->prepare("UPDATE medicos SET nombres=?, apellidos=?, cmp=?, id_especialidad=?, telefono=?, direccion=?, correo=? WHERE id_medico=?");
    $stmt->bind_param("sssisssi", $nombres, $apellidos, $cmp, $id_especialidad, $telefono, $direccion, $correo, $id_medico);
    $successMsg = "✅ Médico actualizado";
} else {
    
    $stmt = $conexion->prepare("INSERT INTO medicos (id_usuario, nombres, apellidos, cmp, id_especialidad, telefono, direccion, correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssisss", $id_usuario, $nombres, $apellidos, $cmp, $id_especialidad, $telefono, $direccion, $correo);
    $successMsg = "✅ Médico registrado con éxito";
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => $successMsg]);
} else {
    echo json_encode(['success' => false, 'error' => '⚠️ Error al guardar: ' . $stmt->error]);
}
