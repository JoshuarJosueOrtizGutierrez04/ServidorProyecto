document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita la recarga de la página

    let messageInput = document.getElementById('message-input');
    let message = messageInput.value;
    let group_id = new URLSearchParams(window.location.search).get('group'); // Obtener el group_id de la URL

    if (message.trim() !== '') {
        // Enviar el mensaje al servidor usando AJAX
        fetch('send_message.php?group=' + group_id, { // Agregar el group_id a la URL
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'message=' + encodeURIComponent(message)
        })
        .then(response => response.json())
        .then(data => {
            updateChatMessages(data);
            messageInput.value = ''; // Limpiar el campo de entrada
        })
        .catch(error => console.error('Error al enviar el mensaje:', error));
    }
});

// Función para actualizar los mensajes del chat
function updateChatMessages(messages) {
    const chatMessages = document.getElementById('chat-messages');
    const atBottom = chatMessages.scrollHeight - chatMessages.scrollTop === chatMessages.clientHeight;

    chatMessages.innerHTML = ''; // Limpiar mensajes antiguos

    messages.forEach(msg => {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message');

        // Comprueba si el mensaje es del usuario actual
        if (msg.is_current_user) {
            messageElement.classList.add('my-message'); // Clase para mensajes del usuario actual
            messageElement.innerHTML = `
                <div class="message-content">
                    <strong>Tú:</strong> ${msg.message}
                </div>
                <span class="timestamp">${msg.created_at}</span>
            `;
        } else {
            messageElement.classList.add('other-message'); // Clase para mensajes de otros usuarios
            messageElement.innerHTML = `
                <div class="message-content">
                    <strong>${msg.username}:</strong> ${msg.message}
                </div>
                <span class="timestamp">${msg.created_at}</span>
            `;
        }

        chatMessages.appendChild(messageElement);
    });

    // Desplazar solo si el usuario estaba al final del chat
    if (atBottom) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

// Cargar mensajes en tiempo real cada segundo
setInterval(() => {
    let group_id = new URLSearchParams(window.location.search).get('group'); // Obtener el group_id de la URL
    fetch('send_message.php?group=' + group_id) // Agregar el group_id a la URL
        .then(response => response.json())
        .then(data => {
            updateChatMessages(data);
        })
        .catch(error => console.error('Error al cargar los mensajes:', error));
}, 1000);


