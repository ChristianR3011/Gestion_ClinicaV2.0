const apiBase = 'php';
let pacientes = [];


function openNewPatientModal() {
  const form = document.getElementById('patientForm');
  form.reset();
  document.getElementById('id_paciente').value = '';
  document.getElementById('newPatientModal').style.display = 'flex';
}

function closeNewPatientModal() {
  document.getElementById('newPatientModal').style.display = 'none';
  document.getElementById('patientForm').reset();
}


async function cargarPacientes() {
  try {
    const res = await fetch(`${apiBase}/obtener_pacientes.php`);
    const data = await res.json();
    pacientes = data;
    renderizarPacientes(data);
  } catch (err) {
    console.error('Error al cargar pacientes:', err);
  }
}


function renderizarPacientes(lista) {
  const tbody = document.getElementById('patients-list');
  tbody.innerHTML = '';

  lista.forEach(p => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${p.id_paciente}</td>
      <td>${p.dni}</td>
      <td>${p.nombres} ${p.apellidos}</td>
      <td>${p.fecha_nacimiento}</td>
      <td>${p.sexo}</td>
      <td>${p.direccion || '---'}</td>
      <td>${p.telefono || '---'}</td>
      <td>${p.correo || '---'}</td>
      <td class="actions">
  <button class="btn btn-sm me-2" onclick="editarPaciente(${p.id_paciente})">✏️</button>
  <button class="btn btn-sm" onclick="deletePaciente(${p.id_paciente})">🗑️</button>
</td>
    `;
    tbody.appendChild(tr);
  });
}


function buscarCoincidencias() {
  const input = document.getElementById('search');
  const term = input.value.trim().toLowerCase();
  const btnMostrarTodos = document.getElementById('btnMostrarTodos');

  if (term === '') {
    alert('Por favor, ingresa un criterio de búsqueda.');
    return;
  }

  const resultados = pacientes.filter(p =>
    (`${p.nombres} ${p.apellidos}`.toLowerCase().includes(term)) ||
    (p.nombres && p.nombres.toLowerCase().includes(term)) ||
    (p.apellidos && p.apellidos.toLowerCase().includes(term)) ||
    (p.dni && p.dni.includes(term)) ||
    (p.correo && p.correo.toLowerCase().includes(term))
  );

  renderizarPacientes(resultados);

  btnMostrarTodos.style.display = resultados.length > 0 ? 'inline-block' : 'none';

  if (resultados.length === 0) {
    alert('❌ No se encontraron coincidencias.');
  }
}


function mostrarTodos() {
  renderizarPacientes(pacientes);
  document.getElementById('search').value = '';
  document.getElementById('btnMostrarTodos').style.display = 'none';
}


function editarPaciente(id) {
  const p = pacientes.find(p => p.id_paciente == id);
  if (!p) return alert('Paciente no encontrado');
  openNewPatientModal();
  document.getElementById('id_paciente').value = p.id_paciente;
  document.getElementById('dni').value = p.dni;
  document.getElementById('nombres').value = p.nombres;
  document.getElementById('apellidos').value = p.apellidos;
  document.getElementById('fecha_nacimiento').value = p.fecha_nacimiento;
  document.getElementById('sexo').value = p.sexo;
  document.getElementById('direccion').value = p.direccion;
  document.getElementById('telefono').value = p.telefono;
  document.getElementById('correo').value = p.correo;
}


async function deletePaciente(id) {
  if (!confirm('¿Estás seguro de eliminar este paciente?')) return;
  try {
    await fetch(`${apiBase}/eliminar_paciente.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${id}`
    });
    cargarPacientes();
  } catch (err) {
    console.error('Error al eliminar paciente:', err);
  }
}


document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('patientForm');
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const datos = new FormData(form);
    const url = datos.get('id_paciente')
      ? 'actualizar_paciente.php'
      : 'guardar_paciente.php';
    try {
      const res = await fetch(`${apiBase}/${url}`, {
        method: 'POST',
        body: new URLSearchParams(datos)
      });
      const text = await res.text();
      if (text.trim() !== 'OK') {
        alert('Error al guardar paciente: ' + text);
        return;
      }
      form.reset();
      closeNewPatientModal();
      cargarPacientes();
    } catch (err) {
      console.error('Error al guardar paciente:', err);
      alert('Error de red al guardar paciente');
    }
  });
  const searchInput = document.getElementById('search');
  searchInput.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
      e.preventDefault();
      buscarCoincidencias();
    }
  });
  cargarPacientes();
});


window.buscarCoincidencias = buscarCoincidencias;
window.mostrarTodos = mostrarTodos;
window.openNewPatientModal = openNewPatientModal;
window.closeNewPatientModal = closeNewPatientModal;
window.editarPaciente = editarPaciente;
window.deletePaciente = deletePaciente;
