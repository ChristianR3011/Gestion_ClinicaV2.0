<?php
require 'conexion.php';

$usuario = $_POST['nombre_usuario'] ?? '';
$clave = $_POST['contraseña'] ?? '';
$rol = $_POST['rol'] ?? '';

$sql = "SELECT * FROM usuarios WHERE nombre_usuario = ? AND contraseña = ? AND rol = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $usuario, $clave, $rol);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "OK";
} else {
    echo "Credenciales incorrectas o rol no coincide";
}

$stmt->close();
?>
