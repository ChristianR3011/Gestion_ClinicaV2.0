async function ajax(url, data = {}) {
    const res = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });
    return res.json();
  }
  
  async function cargarEspecialidades() {
    try {
      const response = await fetch("php/obtener_especialidades.php");
      const especialidades = await response.json();
  
      const lista = document.getElementById("lista-especialidades");
      lista.innerHTML = "";
  
      especialidades.forEach(e => {
        const contenedor = document.createElement("div");
        contenedor.style.display = "flex";
        contenedor.style.justifyContent = "space-between";
        contenedor.style.alignItems = "center";
      
        const enlace = document.createElement("a");
        enlace.href = `medicos_por_especialidad.html?id_especialidad=${e.id_especialidad}`;
        enlace.textContent = e.nombre;
        enlace.className = "specialty-btn";
      
        const acciones = document.createElement("div");
        acciones.className = "acciones-btns";
        acciones.innerHTML = `
          <button onclick="editarEspecialidad(${e.id_especialidad}, '${e.nombre}')">✏️</button>
          <button onclick="eliminarEspecialidad(${e.id_especialidad})">🗑️</button>
        `;
      
        contenedor.appendChild(enlace);
        contenedor.appendChild(acciones);
        lista.appendChild(contenedor);
      });
    } catch (error) {
      console.error("Error al cargar especialidades:", error);
    }
  }
  
  async function agregarEspecialidad() {
    const nombre = document.getElementById("nombre").value.trim();
    if (!nombre) return alert("Escribe un nombre válido.");
  
    const data = await ajax("php/agregar_especialidad.php", { nombre });
    if (data.success) {
      document.getElementById("nombre").value = "";
      cargarEspecialidades();
    } else {
      alert("Error: " + data.error);
    }
  }
  
  async function editarEspecialidad(id, actual) {
    const nuevo = prompt("Editar nombre de especialidad:", actual);
    if (!nuevo || nuevo === actual) return;
  
    const form = new FormData();
form.append("id", id);
form.append("nombre", nuevo);

const response = await fetch("php/actualizar_especialidad.php", {
  method: "POST",
  body: form,
});

const result = await response.text();
if (result === "OK") {
  cargarEspecialidades();
} else {
  alert("Error al actualizar: " + result);
}
  }
  
  async function eliminarEspecialidad(id) {
    if (!confirm("¿Seguro de eliminar esta especialidad?")) return;
  
    const data = await ajax("php/eliminar_especialidad.php", {
      id_especialidad: id
    });
    if (data.success) {
      cargarEspecialidades();
    } else {
      alert("Error: " + data.error);
    }
  }
  
  document.getElementById("btn-agregar").addEventListener("click", agregarEspecialidad);
  document.addEventListener("DOMContentLoaded", cargarEspecialidades);
  
