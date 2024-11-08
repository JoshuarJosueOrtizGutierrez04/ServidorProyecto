// Solicitar permiso para notificaciones si aún no se ha solicitado
if (Notification.permission === "default") {
    Notification.requestPermission().then(permission => {
        if (permission === "granted") {
            console.log("Permiso para notificaciones concedido");
        }
    });
}

// Conjunto para almacenar los mensajes ya notificados
const notifiedMessages = new Set();

// Función para verificar si la pestaña está activa
function isPageHidden() {
    return document.visibilityState === "hidden";
}

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

function mostrarNotificacion(mensaje, usuario) {
    // Solo muestra la notificación si la página está oculta
    if (Notification.permission === "granted" && isPageHidden()) {
        const notification = new Notification("Nuevo mensaje de " + usuario, {
            body: mensaje
        });

        // Agregar evento de clic para enfocar o abrir la pestaña del chat
        notification.onclick = () => {
            window.focus();
        };
    }
}

// Función para actualizar los mensajes del chat
function updateChatMessages(messages) {
    const chatMessages = document.getElementById('chat-messages');
    const atBottom = chatMessages.scrollHeight - chatMessages.scrollTop === chatMessages.clientHeight;

    chatMessages.innerHTML = ''; // Limpiar mensajes antiguos

    messages.forEach(msg => {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message');

        // Identificador único del mensaje
        const messageId = `${msg.username}-${msg.created_at}`;

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

            // Mostrar notificación solo si no se ha notificado antes
            if (!notifiedMessages.has(messageId)) {
                mostrarNotificacion(msg.message, msg.username);
                notifiedMessages.add(messageId); // Agregar a los mensajes notificados
            }
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
