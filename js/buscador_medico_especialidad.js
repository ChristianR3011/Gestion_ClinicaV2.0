const formBuscar = document.getElementById("form-disponibilidad");
const inputIdMedico = document.getElementById("disp-medico");



const especialidadSelect = document.createElement("select");
especialidadSelect.id = "filtro-especialidad";
especialidadSelect.required = true;
especialidadSelect.innerHTML = `<option value="">🔍 Filtrar por especialidad</option>`;
formBuscar.insertBefore(especialidadSelect, formBuscar.firstChild);


const inputBuscar = document.createElement("input");
inputBuscar.type = "text";
inputBuscar.placeholder = "🔎 Buscar médico por nombre o apellido";
inputBuscar.id = "busqueda-medico";
formBuscar.insertBefore(inputBuscar, especialidadSelect.nextSibling);


function cargarEspecialidades() {
  fetch("php/obtener_especialidades.php")
    .then(res => res.json())
    .then(data => {
      data.forEach(e => {
        const option = document.createElement("option");
        option.value = e.id_especialidad;
        option.textContent = e.nombre;
        especialidadSelect.appendChild(option);
      });
    });
}


function filtrarMedicos() {
  const texto = inputBuscar.value.toLowerCase();
  const especialidad = especialidadSelect.value;

  fetch("php/disponibilidad_api.php?...")
    .then(res => res.json())
    .then(data => {
      selectMedico.innerHTML = '<option value="" disabled selected>Seleccione médico</option>';

      const filtrados = data.filter(medico => {
        const nombreCompleto = (medico.nombres + " " + medico.apellidos).toLowerCase();
        const coincideNombre = nombreCompleto.includes(texto);
        const coincideEspecialidad = !especialidad || medico.id_especialidad == especialidad;
        return coincideNombre && coincideEspecialidad;
      });

      filtrados.forEach(medico => {
        const opt = document.createElement("option");
        opt.value = medico.id_medico;
        opt.textContent = medico.nombres + " " + medico.apellidos;
        selectMedico.appendChild(opt);
      });
    });
}


especialidadSelect.addEventListener("change", filtrarMedicos);
inputBuscar.addEventListener("input", filtrarMedicos);

// Iniciar
cargarEspecialidades();
