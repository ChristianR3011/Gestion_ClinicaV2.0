<?php
include('conexion.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conexion->prepare("DELETE FROM pacientes WHERE id_paciente = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "OK";
        } else {
            echo "Error al ejecutar la eliminación: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error al preparar la sentencia: " . $conexion->error;
    }

    $conexion->close();
} else {
    echo "ID no proporcionado.";
}
