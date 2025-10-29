<?php
include('conexion.php');


if (
    isset($_POST['id_paciente'], $_POST['dni'], $_POST['nombres'], $_POST['apellidos'],
          $_POST['fecha_nacimiento'], $_POST['sexo'], $_POST['direccion'],
          $_POST['telefono'], $_POST['correo'])
) {
    $id         = $_POST['id_paciente'];
    $dni        = $_POST['dni'];
    $nombres    = $_POST['nombres'];
    $apellidos  = $_POST['apellidos'];
    $fecha      = $_POST['fecha_nacimiento'];
    $sexo       = $_POST['sexo'];
    $direccion  = $_POST['direccion'];
    $telefono   = $_POST['telefono'];
    $correo     = $_POST['correo'];

    $stmt = $conexion->prepare("UPDATE pacientes SET dni=?, nombres=?, apellidos=?, fecha_nacimiento=?, sexo=?, direccion=?, telefono=?, correo=? WHERE id_paciente=?");

    if ($stmt) {
        $stmt->bind_param("ssssssssi", $dni, $nombres, $apellidos, $fecha, $sexo, $direccion, $telefono, $correo, $id);
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
    echo "Datos incompletos para la actualización.";
}
