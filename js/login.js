document.getElementById('registroForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const form = e.target;
  const fd = new FormData(form);
  const fn = fd.get('fecha_nacimiento');

  if (new Date().getFullYear() - new Date(fn).getFullYear() < 18) {
    return alert('⛔ Debes tener 18 años o más para registrarte.');
  }

  const res = await fetch('php/registrar_paciente.php', {
    method: 'POST',
    body: fd
  });

  const data = await res.json();
  if (data.success) {
    alert('✅ Registro exitoso. Ya puedes iniciar sesión.');
    form.reset();
    cerrarRegistro();
  } else {
    alert('❌ ' + data.error);
  }
});
