const db = require('./db');

class Message {
    static async saveMessage(user, text, room) {
        await db.query('INSERT INTO messages (user, text, room) VALUES (?, ?, ?)', [user, text, room]);
    }

    static async getMessagesByRoom(room) {
        const [rows] = await db.query('SELECT * FROM messages WHERE room = ? ORDER BY createdAt', [room]);
        return rows;
    }
}

module.exports = Message;
