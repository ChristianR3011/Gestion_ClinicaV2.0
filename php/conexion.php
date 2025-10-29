<?php
$conexion = new mysqli("localhost", "root", "", "gestion_clinica", 3306);
if ($conexion->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Error conexión BD: ' . $conexion->connect_error]));
}
$conn = $conexion;
?>
