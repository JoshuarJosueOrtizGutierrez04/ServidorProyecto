const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const db = require('./db'); // Importa la conexión de MySQL

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

app.use(express.static('public'));

// Evento de conexión de socket
io.on('connection', (socket) => {
    console.log('Usuario conectado');

    // Escuchar cuando el usuario se une a una sala
    socket.on('joinRoom', async (data) => {
        const { user, room } = data;
        socket.join(room); // Unir al usuario a la sala especificada
        console.log(`${user} se unió a la sala ${room}`);

        try {
            // Cargar historial de mensajes de la sala específica desde MySQL
            const [messages] = await db.query('SELECT * FROM messages WHERE room = ? ORDER BY timestamp', [room]);
            socket.emit('loadMessages', messages);
        } catch (error) {
            console.error("Error al cargar mensajes:", error);
        }
    });

    // Escuchar el evento de nuevo mensaje
    socket.on('chatMessage', async (data) => {
        console.log("Mensaje recibido en el servidor:", data);

        const { user, text, room } = data;

        try {
            // Guardar el mensaje en la base de datos MySQL
            const [result] = await db.query('INSERT INTO messages (user, text, room) VALUES (?, ?, ?)', [user, text, room]);

            const newMessage = {
                id: result.insertId,
                user,
                text,
                room,
                timestamp: new Date() // Agrega el timestamp del momento
            };

            // Enviar mensaje a todos en la sala
            io.to(room).emit('message', newMessage);
        } catch (error) {
            console.error("Error al guardar mensaje:", error);
        }
    });

    socket.on('disconnect', () => {
        console.log('Usuario desconectado');
    });
});

// Iniciar el servidor
server.listen(3000, () => {
    console.log('Servidor corriendo en http://localhost:3000');
});
