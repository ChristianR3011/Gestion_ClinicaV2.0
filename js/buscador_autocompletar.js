export function iniciarBuscadorPaciente(inputId, callbackSeleccion) {
    const input = document.getElementById(inputId);
  
    input.addEventListener("input", async () => {
      const q = input.value.trim();
      if (q.length < 1) return;
  
      const res = await fetch(`php/buscar_paciente.php?q=${encodeURIComponent(q)}`);
      const data = await res.json();
  
      mostrarSugerencias(input, data, callbackSeleccion);
    });
  }
  
  export function iniciarBuscadorMedico(inputId, callbackSeleccion) {
    const input = document.getElementById(inputId);
  
    input.addEventListener("input", async () => {
      const q = input.value.trim();
      if (q.length < 1) return;
  
      const res = await fetch(`php/buscar_medico_citas_admi.php?q=${encodeURIComponent(q)}`);
      const data = await res.json();
  
      mostrarSugerencias(input, data, callbackSeleccion);
    });
  }
  
  function mostrarSugerencias(input, data, callbackSeleccion) {
    cerrarSugerencias();
  
    const contenedor = document.createElement("div");
    contenedor.classList.add("sugerencias");
  
    if (!data || !Array.isArray(data) || data.length === 0) {
      const vacio = document.createElement("div");
      vacio.textContent = "Sin coincidencias";
      contenedor.appendChild(vacio);
    } else {
      data.forEach(item => {
        const div = document.createElement("div");
        div.textContent = item.nombres + " " + item.apellidos;
        div.addEventListener("click", () => {
          input.value = item.nombres + " " + item.apellidos;
          callbackSeleccion(item);
          cerrarSugerencias();
        });
        contenedor.appendChild(div);
      });
    }
  
    input.parentNode.appendChild(contenedor);
  }
  
  function cerrarSugerencias() {
    const existentes = document.querySelectorAll(".sugerencias");
    existentes.forEach(e => e.remove());
  }
  