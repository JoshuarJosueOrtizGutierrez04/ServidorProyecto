document.addEventListener('DOMContentLoaded', () => {
    // Obtener datos del perfil
    fetch('/profile')
        .then(response => {
            if (response.status === 401) {
                window.location.href = 'login.html'; // Redirige si no está autenticado
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                // Mostrar datos en el HTML
                document.getElementById('username-title').innerText = data.username;
                document.getElementById('username').innerText = data.username;
                document.getElementById('profile-pic').src = data.profile_pic || 'imagenes_perfil/default.png';
                document.getElementById('hobbies').innerText = data.hobbies || 'No especificado';
                document.getElementById('school').innerText = data.school || 'No especificado';
                document.getElementById('about').innerText = data.about || 'No especificado';
                document.getElementById('group-name').innerText = data.group_name;
            }
        })
        .catch(error => console.error('Error al obtener el perfil:', error));
});
