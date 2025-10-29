document.addEventListener('DOMContentLoaded', () => {
  const params    = new URLSearchParams(location.search);
  const idEsp     = params.get('id_especialidad');
  if (!idEsp) {
    alert('Selecciona primero una especialidad');
    history.back();
    return;
  }

  const tabla     = document.getElementById('tablaMedicos');
  const buscador  = document.getElementById('buscador');
  const form      = document.getElementById('formularioMedico');
  const btnNuevo  = document.getElementById('btnNuevo');
  const btnCancel = document.getElementById('btnCancelar');
  const titulo    = document.getElementById('formTitulo');
  let modo = 'nuevo', editCode = '';

  async function ajax(url, opts = {}) {
    const res = await fetch(url, opts);
    return res.json();
  }

  async function cargar(q = '') {
    let url = `../php/obtener_medicos.php?id_especialidad=${idEsp}`;
    if (q) url += `&q=${encodeURIComponent(q)}`;
    const med = await ajax(url);
    tabla.innerHTML = '';
    med.forEach(m => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${m.codigo_medico}</td>
        <td>${m.nombres} ${m.apellidos}</td>
        <td>${m.nombre_especialidad}</td>
        <td>${m.telefono}</td>
        <td>${m.correo}</td>
        <td>
          <button class="btn" data-edit="${m.codigo_medico}">✎</button>
          <button class="btn cancelar" data-del="${m.codigo_medico}">🗑️</button>
        </td>`;
      tabla.appendChild(tr);
    });
    tabla.querySelectorAll('[data-edit]')
         .forEach(b => b.onclick = () => abrirEdit(b.dataset.edit));
    tabla.querySelectorAll('[data-del]')
         .forEach(b => b.onclick   = () => eliminar(b.dataset.del));
  }

  buscador.addEventListener('keypress', e => {
    if (e.key === 'Enter') {
      e.preventDefault();
      cargar(buscador.value.trim());
    }
  });

  btnNuevo.onclick = async () => {
    modo = 'nuevo';
    titulo.textContent = 'Registrar Nuevo Médico';
    form.style.display = 'block';
    form.reset();
    const r = await ajax(`../php/generar_id_medico.php?id_especialidad=${idEsp}`);
    document.getElementById('codigo_medico').value = r.codigo_medico;
  };

  btnCancel.onclick = () => form.style.display = 'none';

  async function abrirEdit(codigo) {
    modo = 'editar'; editCode = codigo;
    titulo.textContent = 'Editar Médico';
    const lista = await ajax(`../php/obtener_medicos.php?id_especialidad=${idEsp}&q=${codigo}`);
    const m = lista[0];
    document.getElementById('codigo_medico').value = m.codigo_medico;
    document.getElementById('nombres').value       = m.nombres;
    document.getElementById('apellidos').value     = m.apellidos;
    document.getElementById('cmp').value           = m.cmp;
    document.getElementById('telefono').value      = m.telefono;
    document.getElementById('direccion').value     = m.direccion;
    document.getElementById('correo').value        = m.correo;
    form.style.display = 'block';
  }

  async function eliminar(codigo) {
    if (!confirm('¿Eliminar este médico?')) return;
    await ajax('php/eliminar_medico.php', {
      method: 'POST',
      headers: { 'Content-Type':'application/json' },
      body: JSON.stringify({ codigo_medico: codigo })
    });
    cargar(buscador.value.trim());
  }

  form.onsubmit = async e => {
    e.preventDefault();
    const data = {
      codigo_medico:   document.getElementById('codigo_medico').value,
      nombres:         document.getElementById('nombres').value,
      apellidos:       document.getElementById('apellidos').value,
      cmp:             document.getElementById('cmp').value,
      id_especialidad: idEsp,
      telefono:        document.getElementById('telefono').value,
      direccion:       document.getElementById('direccion').value,
      correo:          document.getElementById('correo').value,
      modo, editCode
    };
    const url = modo === 'nuevo'
      ? 'php/insertar_medico.php'
      : 'php/actualizar_medico.php';
    await ajax(url, {
      method: 'POST',
      headers: { 'Content-Type':'application/json' },
      body: JSON.stringify(data)
    });
    form.style.display = 'none';
    cargar(buscador.value.trim());
  };

  
  cargar();
});
