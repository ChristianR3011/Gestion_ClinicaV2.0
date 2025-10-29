<?php
require_once "conexion.php";
header('Content-Type: application/json');


if (
    isset($_POST['id_usuario'], $_POST['nombres'], $_POST['apellidos'], $_POST['cmp'],
          $_POST['id_especialidad'], $_POST['telefono'], $_POST['direccion'], $_POST['correo'])
) {
    $id_usuario = $_POST['id_usuario'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $cmp = $_POST['cmp'];
    $id_especialidad = $_POST['id_especialidad'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];

    
    $sql = "INSERT INTO medicos (id_usuario, nombres, apellidos, cmp, id_especialidad, telefono, direccion, correo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("isssisss", $id_usuario, $nombres, $apellidos, $cmp, $id_especialidad, $telefono, $direccion, $correo);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Médico guardado correctamente']);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Faltan campos en el formulario']);
}
?>
