const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const mysql = require('mysql2/promise'); // Importa mysql2 para trabajar con promesas

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

// Configuración de la conexión a MySQL
const dbConfig = {
    host: 'localhost',
    user: 'root',
    password: '', // Cambia la contraseña según tu configuración
    database: 'chat' // Asegúrate de que esta base de datos exista
};

async function getDbConnection() {
    return await mysql.createConnection(dbConfig);
}

app.use(express.static('public'));

// Evento de conexión de socket
io.on('connection', (socket) => {
    console.log('Usuario conectado');

    // Escuchar cuando el usuario se une a una sala
    socket.on('joinRoom', async (data) => {
        const { user, room } = data;
        socket.join(room); // Unir al usuario a la sala especificada
        console.log(`${user} se unió a la sala ${room}`);

        // Cargar historial de mensajes de la sala específica desde MySQL
        try {
            const connection = await getDbConnection();
            const [messages] = await connection.execute(
                'SELECT user, text FROM messages WHERE room = ?',
                [room]
            );
            socket.emit('loadMessages', messages); // Enviar mensajes al cliente
            await connection.end();
        } catch (error) {
            console.error("Error al cargar mensajes:", error);
        }
    });

    // Escuchar el evento de nuevo mensaje
    socket.on('chatMessage', async (data) => {
        console.log("Mensaje recibido en el servidor:", data);

        try {
            const connection = await getDbConnection();
            await connection.execute(
                'INSERT INTO messages (user, text, room) VALUES (?, ?, ?)',
                [data.user, data.text, data.room]
            );
            await connection.end();

            io.to(data.room).emit('message', data); // Enviar mensaje a todos en la sala
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
