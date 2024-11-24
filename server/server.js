const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const User = require('./models/user');
const Message = require('./models/message');
const db = require('./models/db');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

app.use(express.static('public'));
app.use(express.json());

// Ruta para registrar un usuario
app.post('/register', async (req, res) => {
    try {
        const { username, password } = req.body;
        await User.createUser(username, password);
        res.sendStatus(201);
    } catch (error) {
        console.error(error);
        res.sendStatus(500);
    }
});

// Ruta para iniciar sesión
app.post('/login', async (req, res) => {
    const { username, password } = req.body;
    const user = await User.findByUsername(username);
    
    if (!user || !(await bcrypt.compare(password, user.password))) {
        return res.status(401).json({ error: 'Credenciales incorrectas' });
    }

    const token = jwt.sign({ id: user.id, username: user.username }, 'secreto', { expiresIn: '1h' });
    res.json({ token });
});

// Conexión de WebSocket
io.on('connection', (socket) => {
    socket.on('joinRoom', async ({ username, room }) => {
        socket.join(room);

        const messages = await Message.getMessagesByRoom(room);
        socket.emit('loadMessages', messages);
    });

    socket.on('chatMessage', async ({ username, text, room }) => {
        await Message.saveMessage(username, text, room);
        io.to(room).emit('message', { username, text });
    });
});

server.listen(3000, () => {
    console.log('Servidor corriendo en http://localhost:3000');
});
