import { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';

// ...existing code...

function App() {
    const [isAuthenticated, setIsAuthenticated] = useState(false);

    useEffect(() => {
        // Check if user is authenticated on component mount
        const checkAuth = async () => {
            try {
                const response = await fetch('/api/auth/check-auth', {
                    credentials: 'include'
                });
                setIsAuthenticated(response.ok);
            } catch (error) {
                setIsAuthenticated(false);
            }
        };
        checkAuth();
    }, []);

    const handleLogout = async () => {
        try {
            const response = await fetch('/api/auth/logout', {
                method: 'POST',
                credentials: 'include'
            });

            if (response.ok) {
                setIsAuthenticated(false);
                window.location.href = '/login';
            }
        } catch (error) {
            console.error('Logout error:', error);
        }
    };

    return (
        <Router>
            <Routes>
                <Route 
                    path="/" 
                    element={
                        isAuthenticated ? (
                            <Home onLogout={handleLogout} />
                        ) : (
                            <Navigate to="/login" replace />
                        )
                    } 
                />
                <Route 
                    path="/login" 
                    element={
                        isAuthenticated ? (
                            <Navigate to="/" replace />
                        ) : (
                            <Login setIsAuthenticated={setIsAuthenticated} />
                        )
                    } 
                />
            </Routes>
        </Router>
    );
}

export default App;

// In your button or component:
<button onClick={handleLogout}>Logout</button>

// ...existing code...
