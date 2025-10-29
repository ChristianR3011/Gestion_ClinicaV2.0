<?php
require_once "conexion.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id_especialidad"] ?? null;

$response = ["success" => false];

try {
    if (!$id) {
        throw new Exception("ID no proporcionado.");
    }

    // Verificaremos en mi codigo si hay médicos asignados
    $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM medicos WHERE id_especialidad = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc()["total"];
    $stmt->close();

    if ($total > 0) {
        $response["error"] = "No se puede eliminar. Hay médicos asignados a esta especialidad.";
    } else {
        $stmt = $conexion->prepare("DELETE FROM especialidades WHERE id_especialidad = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $response["success"] = true;
        } else {
            $response["error"] = "Error al eliminar: " . $stmt->error;
        }

        $stmt->close();
    }
} catch (Exception $e) {
    $response["error"] = "Excepción: " . $e->getMessage();
}

echo json_encode($response);
?>
