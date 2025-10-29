<?php
$conexion = new mysqli("localhost", "root", "", "gestion_clinica");
$conexion->set_charset("utf8");

if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["listar"])) {
  $res = $conexion->query("SELECT id_usuario, nombre_usuario, rol FROM usuarios WHERE rol IN ('admin', 'medico')");
  $datos = [];
  while ($fila = $res->fetch_assoc()) {
    $datos[] = $fila;
  }
  echo json_encode($datos);
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $action = $_POST['action'] ?? '';

  if ($action === 'guardar') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];

    if ($id_usuario) {
      // Update
      $stmt = $conexion->prepare("UPDATE usuarios SET nombre_usuario = ?, contrasena = ?, rol = ? WHERE id_usuario = ?");
      $stmt->bind_param("sssi", $nombre_usuario, $contrasena, $rol, $id_usuario);
    } else {
      // Insert
      $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, contrasena, rol) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $nombre_usuario, $contrasena, $rol);
    }
    if ($stmt->execute()) {
      $stmt->close();
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
  } elseif ($action === 'eliminar') {
    $id_usuario = $_POST['id_usuario'];
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    if ($stmt->execute()) {
      $stmt->close();
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
  }
  exit;
}
?>
