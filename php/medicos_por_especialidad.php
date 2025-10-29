<?php
require_once "conexion.php";

$id_especialidad = isset($_GET['id_especialidad']) ? intval($_GET['id_especialidad']) : 0;

$stmt = $conn->prepare("SELECT nombre FROM especialidades WHERE id_especialidad = ?");
$stmt->execute([$id_especialidad]);
$especialidad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$especialidad) {
    echo "Especialidad no encontrada.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Médicos - <?php echo htmlspecialchars($especialidad['nombre']); ?></title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f8ff;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 1100px;
      margin: 30px auto;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h1 {
      color: #0077b6;
    }
    .acciones {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .btn {
      padding: 10px 16px;
      border: none;
      background-color: #0077b6;
      color: white;
      border-radius: 6px;
      cursor: pointer;
    }
    .btn.cancelar {
      background-color: #ccc;
      color: black;
    }
    input[type="text"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #0077b6;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="acciones">
      <button class="btn cancelar" onclick="window.location.href='../especialidades.html'">← Volver a Especialidades</button>
      <button class="btn" onclick="location.href='nuevo_medico.php?id_especialidad=<?php echo $id_especialidad; ?>'">+ Nuevo Médico</button>
    </div>

    <h1>Médicos de <?php echo htmlspecialchars($especialidad['nombre']); ?></h1>

    <input type="text" id="busqueda" placeholder="Buscar por nombre o apellido...">

    <table>
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tablaMedicos"></tbody>
    </table>
  </div>

  <script>
    const id_especialidad = <?php echo $id_especialidad; ?>;

    async function cargarMedicos(filtro = "") {
      const res = await fetch(`obtener_medicos_por_especialidad.php?id_especialidad=${id_especialidad}&busqueda=${encodeURIComponent(filtro)}`);
      const medicos = await res.json();
      const tbody = document.getElementById("tablaMedicos");
      tbody.innerHTML = "";

      if (medicos.length === 0) {
        tbody.innerHTML = "<tr><td colspan='5'>No se encontraron médicos.</td></tr>";
        return;
      }

      medicos.forEach(m => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
          <td>${m.codigo_medico}</td>
          <td>${m.nombres} ${m.apellidos}</td>
          <td>${m.telefono}</td>
          <td>${m.correo}</td>
          <td>
            <button onclick="editar(${m.id_medico})">✏️</button>
            <button onclick="eliminar(${m.id_medico})">🗑️</button>
          </td>
        `;
        tbody.appendChild(fila);
      });
    }

    function editar(id) {
      location.href = "editar_medico.php?id=" + id;
    }

    async function eliminar(id) {
      if (!confirm("¿Seguro que deseas eliminar este médico?")) return;
      const res = await fetch("eliminar_medico.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_medico=" + id
      });
      const data = await res.json();
      if (data.success) {
        alert("Médico eliminado");
        cargarMedicos(document.getElementById("busqueda").value);
      } else {
        alert("Error: " + data.error);
      }
    }

    document.getElementById("busqueda").addEventListener("input", e => {
      cargarMedicos(e.target.value);
    });

    document.addEventListener("DOMContentLoaded", () => {
      cargarMedicos();
    });
  </script>
</body>
</html>
