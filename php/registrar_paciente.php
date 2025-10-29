<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success'=>false,'error'=>'Método no permitido']);
  exit;
}

$campos = ['dni','nombres','apellidos','fecha_nacimiento','sexo','telefono','correo','contrasena'];
foreach($campos as $c){
  if (!isset($_POST[$c]) || trim($_POST[$c])==='') {
    echo json_encode(['success'=>false,'error'=>"El campo '$c' está vacío."]);
    exit;
  }
}

$dni    = $_POST['dni'];
$nombres= $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$fecha  = $_POST['fecha_nacimiento'];
$sexo   = $_POST['sexo'];
$direccion = $_POST['direccion'] ?? null;
$telefono  = $_POST['telefono'];
$correo    = $_POST['correo'];
$contrasena= $_POST['contrasena'];
$rol       = 'paciente';


$stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, contrasena, rol) VALUES (?, ?, ?)");
$stmt->bind_param("sss",$correo,$contrasena,$rol);
if (!$stmt->execute()){
  echo json_encode(['success'=>false,'error'=>"Error en usuarios: ".$stmt->error]);
  exit;
}
$id_usuario = $conexion->insert_id;


$stmt2 = $conexion->prepare("
  INSERT INTO pacientes
    (dni,nombres,apellidos,fecha_nacimiento,sexo,direccion,telefono,correo,id_usuario)
  VALUES (?,?,?,?,?,?,?,?,?)
");
$stmt2->bind_param(
    "ssssssssi",  
    $dni,
    $nombres,
    $apellidos,
    $fecha,
    $sexo,
    $direccion,
    $telefono,
    $correo,
    $id_usuario
  );
if (!$stmt2->execute()){
  echo json_encode(['success'=>false,'error'=>"Error en pacientes: ".$stmt2->error]);
  exit;
}

echo json_encode(['success'=>true]);
