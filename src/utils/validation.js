const validateLoginInput = (username, password) => {
    const errors = {};

    if (!username.trim()) {
        errors.username = 'Username is required';
    }

    if (!password) {
        errors.password = 'Password is required';
    } else if (password.length < 8) {
        errors.password = 'Password must be at least 8 characters';
    }

    return {
        isValid: Object.keys(errors).length === 0,
        errors
    };
};

module.exports = { validateLoginInput };
