let historias = [];
let paginaActual = 1;
const porPagina = 3;
let pacienteSeleccionado = null;


document.getElementById("fecha-actualizacion").textContent = new Date().toLocaleDateString();

$(function () {
  $("#buscarPaciente").autocomplete({
    source(request, response) {
      $.getJSON("php/buscar_paciente_autocompletar_historiaC.php", { q: request.term }, data => {
        response(data.map(p => ({
          label: `${p.nombres} ${p.apellidos}`,
          value: `${p.nombres} ${p.apellidos}`,
          id: p.id_paciente
        })));
      });
    },
    select(event, ui) {
      pacienteSeleccionado = ui.item.id;
      cargarHistoria(ui.item.id);
    }
  });
});

async function cargarHistoria(id_paciente) {
  const desde = document.getElementById("filtroDesde").value;
  const hasta = document.getElementById("filtroHasta").value;
  let url = `php/buscar_paciente_historiaclinica.php?id_paciente=${id_paciente}`;
  if (desde && hasta) url += `&desde=${desde}&hasta=${hasta}`;

  const res = await fetch(url);
  const data = await res.json();
  if (!data.success) return alert("No se encontraron historias.");

  mostrarDatosPaciente(data.paciente);
  historias = data.historias || [];
  paginaActual = 1;
  mostrarPaginadas();
}

function mostrarDatosPaciente(paciente) {
  const cont = document.getElementById("tarjetaPaciente");
  cont.style.display = "grid";
  cont.innerHTML = `
    <div><span class="etiqueta">👤 Nombres:</span><div class="valor-celda">${paciente.nombres} ${paciente.apellidos}</div></div>
    <div><span class="etiqueta">📧 Correo:</span><div class="valor-celda">${paciente.correo || "-"}</div></div>
    <div><span class="etiqueta">📞 Teléfono:</span><div class="valor-celda">${paciente.telefono || "-"}</div></div>
    <div><span class="etiqueta">📅 Nacimiento:</span><div class="valor-celda">${paciente.fecha_nacimiento}</div></div>
    <div><span class="etiqueta">🏠 Dirección:</span><div class="valor-celda">${paciente.direccion || "-"}</div></div>
  `;
}

function mostrarPaginadas() {
  const start = (paginaActual - 1) * porPagina;
  const slice = historias.slice(start, start + porPagina);
  const cont = document.getElementById("lineaTiempo");
  cont.innerHTML = "";

  if (slice.length === 0) {
    cont.innerHTML = "<p>No hay historias para este rango.</p>";
    return;
  }

  slice.forEach(h => {
    const div = document.createElement("div");
    div.className = "evento";
    
    let botones = `<button class="btn" onclick='generarPDF(${JSON.stringify(h)})'>📄 PDF</button>`;
    if (h.estado !== "atendida") {
      botones += ` <button class="btn" onclick='mostrarFormularioEdicion(${JSON.stringify(h)})'>✏️ Editar Historia Clínica</button>`;
    }
    div.innerHTML = `
      <p><strong>📅 Fecha:</strong> ${h.fecha_registro}</p>
      <p><strong>🩺 Médico:</strong> ${h.nombre_medico}</p>
      <p><strong>📝 Motivo:</strong> ${h.motivo_consulta}</p>
      <p><strong>📋 Diagnóstico:</strong> ${h.diagnostico || "-"}</p>
      <p><strong>💊 Tratamiento:</strong> ${h.tratamiento || "-"}</p>
      <p><strong>🔄 Estado:</strong> ${h.estado}</p>
      <div class="acciones-evento">${botones}</div>
    `;
    cont.appendChild(div);
  });

  
  const total = Math.ceil(historias.length / porPagina);
  const nav = document.createElement("div");
  nav.style.marginTop = "1rem";
  nav.innerHTML = `
    <button class="btn" ${paginaActual <= 1 ? "disabled" : ""} onclick="cambiarPagina(-1)">⬅ Anterior</button>
    Página ${paginaActual} de ${total}
    <button class="btn" ${paginaActual >= total ? "disabled" : ""} onclick="cambiarPagina(1)">Siguiente ➡</button>
  `;
  cont.appendChild(nav);
}

function cambiarPagina(d) {
  paginaActual += d;
  mostrarPaginadas();
}

function generarPDF(historia) {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  doc.setFontSize(16).text("🩺 Historia Clínica Digital", 10, 15);
  doc.setFontSize(12)
     .text(`📅 Fecha: ${historia.fecha_registro}`, 10, 30)
     .text(`👨‍⚕️ Médico: ${historia.nombre_medico}`, 10, 40)
     .text(`📝 Motivo: ${historia.motivo_consulta || "-"}`, 10, 50, { maxWidth: 170 })
     .text(`📋 Diagnóstico: ${historia.diagnostico || "-"}`, 10, 70, { maxWidth: 170 })
     .text(`💊 Tratamiento: ${historia.tratamiento || "-"}`, 10, 90, { maxWidth: 170 });
  doc.save(`historia_${historia.id_historia}.pdf`);
}

function aplicarFiltroFechas() {
  if (pacienteSeleccionado) cargarHistoria(pacienteSeleccionado);
  else alert("Selecciona un paciente primero");
}

function mostrarFormularioEdicion(historia) {
  
  const modal = document.createElement("div");
  modal.id = "modalEditar";
  modal.style = `
    position: fixed; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.6);
    display:flex; align-items:center; justify-content:center;
    z-index:9999;
  `;
  modal.innerHTML = `
    <div style="background:#fff;padding:2rem;border-radius:15px;max-width:500px;width:100%;">
      <h3>✏️ Editar Historia Clínica</h3>
      <label>Motivo:<br><textarea id="editMotivo" style="width:100%;height:4rem;">${historia.motivo_consulta}</textarea></label><br><br>
      <label>Diagnóstico:<br><textarea id="editDiagnostico" style="width:100%;height:4rem;">${historia.diagnostico||""}</textarea></label><br><br>
      <label>Tratamiento:<br><textarea id="editTratamiento" style="width:100%;height:4rem;">${historia.tratamiento||""}</textarea></label><br><br>
      <label style="display:flex;align-items:center;gap:0.5rem;">
        <input type="checkbox" id="editAtendida" ${historia.estado==="atendida"?"checked disabled":""}/>
        <span>Marcar como atendida</span>
      </label><br><br>
      <div style="text-align:right;">
        <button class="btn" onclick="guardarEdicion(${historia.id_historia},${historia.id_cita})">💾 Guardar</button>
        <button class="btn" onclick="cerrarModal()">❌ Cancelar</button>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

function cerrarModal() {
  const m = document.getElementById("modalEditar");
  if (m) m.remove();
}

async function guardarEdicion(id_historia, id_cita) {
  const motivo      = document.getElementById("editMotivo").value.trim();
  const diagnostico = document.getElementById("editDiagnostico").value.trim();
  const tratamiento = document.getElementById("editTratamiento").value.trim();
  const atendidaChk = document.getElementById("editAtendida");
  const marcarAtend = atendidaChk && atendidaChk.checked;

  
  let res = await fetch("php/editar_historia.php", {
    method:"POST",
    headers:{"Content-Type":"application/json"},
    body:JSON.stringify({id_historia,motivo,diagnostico,tratamiento})
  });
  let j = await res.json();
  if (!j.success) {
    return alert("Error actualizando historia: "+j.error);
  }

  
  if (marcarAtend) {
    res = await fetch("php/marcar_cita_atendida.php", {
      method:"POST",
      headers:{"Content-Type":"application/x-www-form-urlencoded"},
      body:`id_cita=${id_cita}`
    });
    j = await res.json();
    if (!j.success) {
      return alert("Historia guardada pero no se marcó cita: "+j.error);
    }
  }

  cerrarModal();
  alert("✅ Historia actualizada"+(marcarAtend?" y cita atendida":""));
  cargarHistoria(pacienteSeleccionado);
}
