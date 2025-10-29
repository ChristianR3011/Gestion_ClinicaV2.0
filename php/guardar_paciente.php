<?php
include('conexion.php');

// Validar que existan los campos requeridos
if (
    isset($_POST['dni'], $_POST['nombres'], $_POST['apellidos'], $_POST['fecha_nacimiento'],
    $_POST['sexo'], $_POST['direccion'], $_POST['telefono'], $_POST['correo'])
) {
    $dni        = $_POST['dni'];
    $nombres    = $_POST['nombres'];
    $apellidos  = $_POST['apellidos'];
    $fecha      = $_POST['fecha_nacimiento'];
    $sexo       = $_POST['sexo'];
    $direccion  = $_POST['direccion'];
    $telefono   = $_POST['telefono'];
    $correo     = $_POST['correo'];

    $stmt = $conexion->prepare("INSERT INTO pacientes (dni, nombres, apellidos, fecha_nacimiento, sexo, direccion, telefono, correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssssssss", $dni, $nombres, $apellidos, $fecha, $sexo, $direccion, $telefono, $correo);
        if ($stmt->execute()) {
            echo "OK";
        } else {
            echo "Error al ejecutar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación: " . $conexion->error;
    }

    $conexion->close();
} else {
    echo "Datos incompletos.";
}
