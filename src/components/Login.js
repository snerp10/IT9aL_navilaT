import { useNavigate } from 'react-router-dom';

function Login({ setIsAuthenticated }) {
    const navigate = useNavigate();

    const handleLogin = async (credentials) => {
        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(credentials),
                credentials: 'include'
            });

            if (response.ok) {
                setIsAuthenticated(true);
                navigate('/', { replace: true });
            }
        } catch (error) {
            console.error('Login error:', error);
        }
    };

    // ...existing code...
}
