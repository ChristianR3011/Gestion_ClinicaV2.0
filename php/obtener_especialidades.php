<?php
require_once "conexion.php";

$sql = "SELECT id_especialidad, nombre FROM especialidades";
$resultado = $conexion->query($sql);

$especialidades = [];

while ($fila = $resultado->fetch_assoc()) {
    $especialidades[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($especialidades);
