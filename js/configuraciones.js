const apiBase = 'php/configuraciones.php';
let usuarios = [];

function toggleAddForm() {
  document.getElementById('addForm').classList.toggle('show');
}

function openNewUser() {
  const form = document.getElementById('userForm');
  form.reset();
  document.getElementById('id_usuario').value = '';
  toggleAddForm();
}

async function cargarUsuarios() {
  try {
    const res = await fetch(`${apiBase}?listar`);
    const data = await res.json();
    usuarios = data;
    renderizarUsuarios(data);
  } catch (err) {
    console.error('Error al cargar usuarios:', err);
  }
}

function renderizarUsuarios(lista) {
  const tbody = document.getElementById('users-list');
  tbody.innerHTML = '';

  lista.forEach(u => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${u.id_usuario}</td>
      <td>${u.nombre_usuario}</td>
      <td>${u.rol === 'medico' ? 'Médico' : 'Administrativo'}</td>
      <td class="actions">
        <button class="btn btn-sm me-2" onclick="editarUsuario(${u.id_usuario})">✏️</button>
        <button class="btn btn-sm" onclick="deleteUsuario(${u.id_usuario})">🗑️</button>
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

  const resultados = usuarios.filter(u =>
    (u.nombre_usuario && u.nombre_usuario.toLowerCase().includes(term)) ||
    (u.rol && u.rol.toLowerCase().includes(term))
  );

  renderizarUsuarios(resultados);

  btnMostrarTodos.style.display = resultados.length > 0 ? 'inline-block' : 'none';

  if (resultados.length === 0) {
    alert('❌ No se encontraron coincidencias.');
  }
}

function mostrarTodos() {
  renderizarUsuarios(usuarios);
  document.getElementById('search').value = '';
  document.getElementById('btnMostrarTodos').style.display = 'none';
}

function editarUsuario(id) {
  const u = usuarios.find(u => u.id_usuario == id);
  if (!u) return alert('Usuario no encontrado');
  toggleAddForm();
  document.getElementById('id_usuario').value = u.id_usuario;
  document.getElementById('nombre_usuario').value = u.nombre_usuario;
  document.getElementById('contrasena').value = ''; // Don't show password
  document.getElementById('rol').value = u.rol;
}

async function deleteUsuario(id) {
  if (!confirm('¿Estás seguro de eliminar este usuario?')) return;
  try {
    const res = await fetch(`${apiBase}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=eliminar&id_usuario=${id}`
    });
    const data = await res.json();
    if (data.success) {
      cargarUsuarios();
    } else {
      alert('Error al eliminar usuario');
    }
  } catch (err) {
    console.error('Error al eliminar usuario:', err);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('userForm');
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const datos = new FormData(form);
    datos.append('action', 'guardar');
    try {
      const res = await fetch(`${apiBase}`, {
        method: 'POST',
        body: new URLSearchParams(datos)
      });
      const data = await res.json();
      if (data.success) {
        form.reset();
        toggleAddForm();
        cargarUsuarios();
      } else {
        alert('Error al guardar usuario: ' + (data.error || 'Desconocido'));
      }
    } catch (err) {
      console.error('Error al guardar usuario:', err);
      alert('Error de red al guardar usuario');
    }
  });
  const searchInput = document.getElementById('search');
  searchInput.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
      e.preventDefault();
      buscarCoincidencias();
    }
  });
  cargarUsuarios();
});

window.buscarCoincidencias = buscarCoincidencias;
window.mostrarTodos = mostrarTodos;
window.openNewUser = openNewUser;
window.toggleAddForm = toggleAddForm;
window.editarUsuario = editarUsuario;
window.deleteUsuario = deleteUsuario;
