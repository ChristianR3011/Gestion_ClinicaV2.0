<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "gestion_clinica";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8");

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Error de conexión"]);
  exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
  $data = json_decode(file_get_contents("php://input"), true);
  
  if (isset($data["eliminar"]) && $data["eliminar"] === true && isset($data["id_disponibilidad"])) {
    $stmt = $conn->prepare("DELETE FROM disponibilidad_medica WHERE id_disponibilidad = ?");
    $stmt->bind_param("i", $data["id_disponibilidad"]);
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit;
  }
}

// ✅ SE  VA A INSERTAR o ACTUALIZAR (POST FORM)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST["id_disponibilidad"] ?? '';
  $id_medico = $_POST["medico"];
  $fecha = $_POST["fecha"];
  $hora_inicio = $_POST["hora_inicio"];
  $hora_fin = $_POST["hora_fin"];

  if ($id) {
    // EDITAR
    $stmt = $conn->prepare("UPDATE disponibilidad_medica SET id_medico=?, fecha=?, hora_inicio=?, hora_fin=? WHERE id_disponibilidad=?");
    $stmt->bind_param("isssi", $id_medico, $fecha, $hora_inicio, $hora_fin, $id);
  } else {
    // GUARDAR NUEVO
    $stmt = $conn->prepare("INSERT INTO disponibilidad_medica (id_medico, fecha, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id_medico, $fecha, $hora_inicio, $hora_fin);
  }

  if ($stmt->execute()) {
    echo json_encode(["success" => true]);
  } else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
  }

  exit;
}

// ✅ LISTAR MÉDICOS
if (isset($_GET["medicos"])) {
  $result = $conn->query("SELECT id_medico, nombres, apellidos, id_especialidad FROM medicos");
  $medicos = [];
  while ($row = $result->fetch_assoc()) {
    $medicos[] = $row;
  }
  echo json_encode($medicos);
  exit;
}

// ✅ LISTAR DISPONIBILIDAD
if (isset($_GET["listar"])) {
  $sql = "SELECT d.id_disponibilidad, d.id_medico, CONCAT(m.nombres, ' ', m.apellidos) AS medico, d.fecha, d.hora_inicio, d.hora_fin
          FROM disponibilidad_medica d
          JOIN medicos m ON d.id_medico = m.id_medico
          ORDER BY d.fecha ASC, d.hora_inicio ASC";
  $result = $conn->query($sql);
  $disponibilidad = [];
  while ($row = $result->fetch_assoc()) {
    $disponibilidad[] = $row;
  }
  echo json_encode($disponibilidad);
  exit;
}
?>
