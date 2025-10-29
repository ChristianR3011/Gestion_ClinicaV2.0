const apiBase = 'php';

document.addEventListener('DOMContentLoaded', () => {
  cargarPacientes();
  cargarMedicos();
  document.getElementById('citaForm').addEventListener('submit', guardarCita);
});

async function cargarPacientes() {
  try {
    const res = await fetch(`${apiBase}/obtener_pacientes.php`);
    const data = await res.json();
    const sel = document.getElementById('id_paciente');
    sel.innerHTML = '<option value="">-- Selecciona Paciente --</option>';
    data.forEach(p => {
      const option = document.createElement('option');
      option.value = p.id_paciente;
      option.textContent = `${p.nombres} ${p.apellidos}`;
      sel.appendChild(option);
    });
  } catch (err) {
    console.error('Error al cargar pacientes:', err);
  }
}

async function cargarMedicos() {
  try {
    const res = await fetch(`${apiBase}/obtener_medicos.php`);
    const data = await res.json();
    const sel = document.getElementById('id_medico');
    sel.innerHTML = '<option value="">-- Selecciona Médico --</option>';
    data.forEach(m => {
      const option = document.createElement('option');
      option.value = m.id_medico;
      option.textContent = `${m.nombres} ${m.apellidos}`;
      sel.appendChild(option);
    });
  } catch (err) {
    console.error('Error al cargar médicos:', err);
  }
}

async function guardarCita(e) {
  e.preventDefault();
  const form = new FormData(e.target);
  try {
    const res = await fetch(`${apiBase}/guardar_cita.php`, {
      method: 'POST',
      body: new URLSearchParams(form)
    });
    const text = await res.text();
    if (text.trim() !== 'OK') {
      alert('Error al registrar cita: ' + text);
      return;
    }

    const historia = new URLSearchParams({
      id_paciente: form.get('id_paciente'),
      fecha_registro: form.get('fecha_cita'),
      motivo_consulta: form.get('motivo_consulta'),
      diagnostico: form.get('diagnostico'),
      tratamiento: '',
      id_medico: form.get('id_medico')
    });

    const res2 = await fetch(`${apiBase}/guardar_historia.php`, {
      method: 'POST',
      body: historia
    });

    const text2 = await res2.text();
    if (text2.trim() !== 'OK') {
      alert('Cita registrada, pero error en historia: ' + text2);
      return;
    }

    alert('✅ Cita y Historia registradas correctamente');
    e.target.reset();
  } catch (err) {
    console.error('Error en guardarCita:', err);
    alert('Fallo al registrar la cita');
  }
}
