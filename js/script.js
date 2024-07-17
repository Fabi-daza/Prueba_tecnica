const selectRegiones = document.getElementById('region')
const selectCandidatos = document.getElementById('candidato')
const selectComunas = document.getElementById('comuna')
const formVotacion = document.getElementById('form')

document.addEventListener('DOMContentLoaded', function() {
   getRegiones();
   getCandidatos();
});

selectRegiones.addEventListener('change',function() {
    const region_id = this.value
    selectComunas.innerHTML = '<option value="">Seleccione una comuna</option>';
    getComunas(region_id);
})

formVotacion.addEventListener('submit', function(event){
    votacion(event, this)
})


function getRegiones(){

    fetch('./php/get_regiones.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(region => {
                const option = document.createElement('option')
                option.value = region.id
                option.textContent = region.nombre
                selectRegiones.appendChild(option)
            });
        })
        .catch(error => {
            console.error('Error al obtener las regiones',error)
        })
}

function getCandidatos(){

    fetch('./php/get_candidatos.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(candidato => {
                const option = document.createElement('option')
                option.value = candidato.id
                option.textContent = candidato.nombre
                selectCandidatos.appendChild(option)
            });
        })
        .catch(error => {
            console.error('Error al obtener los candidatos',error)
        })
}

function getComunas(region){
    fetch(`./php/get_comunas.php?region_id=${region}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(comuna => {
                const option = document.createElement('option')
                option.value = comuna.id
                option.textContent = comuna.nombre
                selectComunas.appendChild(option)
            })
        })
        .catch(error => {
            console.error('Error al obtener las regiones',error)
        })
}

function votacion(event, form){
    event.preventDefault();

    const formData = new FormData(form);

    fetch('./php/votacion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Gracias por tu voto');
            form.reset();
        } else {
            alert('Error al enviar tu voto: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}