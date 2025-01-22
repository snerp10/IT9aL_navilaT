const express = require('express');
const router = express.Router();
const { login, logout, checkAuth } = require('../controllers/authController');

// ...existing code...
router.post('/login', login);
router.post('/logout', logout);
router.get('/check-auth', checkAuth);

module.exports = router;