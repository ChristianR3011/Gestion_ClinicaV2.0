<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST["nombre"]);

  if (!empty($nombre)) {
    $stmt = $conexion->prepare("INSERT INTO especialidades (nombre) VALUES (?)");

    if (!$stmt) {
      echo "Error al preparar: " . $conexion->error;
      exit;
    }

    $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {
      echo "OK";
    } else {
      echo "Error al insertar: " . $stmt->error;
    }

    $stmt->close();
  } else {
    echo "Nombre vacío";
  }
} else {
  echo "Método no permitido";
}
?>
