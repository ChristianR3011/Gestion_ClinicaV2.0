<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
session_start(); 

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['usuario'], $data['contrasena'], $data['rol'])) {
    echo json_encode(["success" => false, "error" => "Faltan datos"]);
    exit;
}

$usuario    = $data['usuario'];
$contrasena = $data['contrasena'];
$rol        = $data['rol'];

$conn = new mysqli("localhost", "root", "", "gestion_clinica");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Error de conexión"]);
    exit;
}

// Verificar usuario
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ? AND contrasena = ? AND rol = ?");
$stmt->bind_param("sss", $usuario, $contrasena, $rol);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Guarda en $_SESSION antes de enviar cualquier salida
    $_SESSION['id_usuario'] = $user['id_usuario'];
    $_SESSION['rol']       = $user['rol'];

    // Obtén el nombre para el saludo si es paciente
    $nombre = $usuario;
    if ($rol === "paciente") {
        $stmt2 = $conn->prepare("SELECT CONCAT(nombres, ' ', apellidos) AS nombre FROM pacientes WHERE correo = ? OR telefono = ?");
        $stmt2->bind_param("ss", $usuario, $usuario);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        if ($res2->num_rows === 1) {
            $row = $res2->fetch_assoc();
            $nombre = $row['nombre'];
        }
    }

    echo json_encode([
        "success"    => true,
        "rol"        => $user['rol'],
        "nombre"     => $nombre,
        "id_usuario" => $user['id_usuario']
    ]);
} else {
    echo json_encode(["success" => false, "error" => "Credenciales incorrectas"]);
}

$conn->close();

