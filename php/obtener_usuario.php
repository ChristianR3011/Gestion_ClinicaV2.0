<?php
require_once "conexion.php";

$sql = "SELECT id_usuario, nombre_usuario FROM usuarios";
$resultado = $conexion->query($sql);

$usuarios = [];

while ($fila = $resultado->fetch_assoc()) {
    $usuarios[] = $fila;
}

echo json_encode($usuarios);
?>
