const mysql = require('mysql2');

const db = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: 'Bucks1968',
    database: 'chat'
});

module.exports = db.promise();
