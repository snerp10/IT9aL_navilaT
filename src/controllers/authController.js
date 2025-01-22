const bcrypt = require('bcrypt');
const { validateLoginInput } = require('../utils/validation');
const db = require('../database/connection');

const login = async (req, res) => {
    try {
        const { username, password } = req.body;
        const { isValid, errors } = validateLoginInput(username, password);

        if (!isValid) {
            return res.status(400).json({ errors });
        }

        const user = await db.query('SELECT * FROM users WHERE username = ?', [username]);

        if (!user.length) {
            return res.status(401).json({ message: 'Invalid credentials' });
        }

        const isPasswordValid = await bcrypt.compare(password, user[0].password_hash);

        if (!isPasswordValid) {
            await db.query('UPDATE users SET login_attempts = login_attempts + 1 WHERE id = ?', [user[0].id]);
            return res.status(401).json({ message: 'Invalid credentials' });
        }

        // Reset login attempts and update last login
        await db.query('UPDATE users SET login_attempts = 0, last_login = NOW() WHERE id = ?', [user[0].id]);

        req.session.userId = user[0].id;
        res.json({ message: 'Login successful' });
    } catch (error) {
        console.error('Login error:', error);
        res.status(500).json({ message: 'Server error' });
    }
};

const logout = async (req, res) => {
    req.session.destroy((err) => {
        if (err) {
            return res.status(500).json({ message: 'Error logging out' });
        }
        res.clearCookie('connect.sid'); // Clear the session cookie
        res.json({ message: 'Logged out successfully' });
    });
};

const checkAuth = async (req, res) => {
    if (req.session.userId) {
        res.status(200).json({ authenticated: true });
    } else {
        res.status(401).json({ authenticated: false });
    }
};

module.exports = { 
    login,
    logout,
    checkAuth
};
