<div class="logo-container">
    <svg width="200" height="60" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
        <!-- Background Shape -->
        <rect x="10" y="10" width="180" height="40" rx="8" fill="#1a365d" />
        
        <!-- Main Text -->
        <text x="30" y="40" font-family="Arial, sans-serif" font-size="30" font-weight="bold" fill="white">
            navila
        </text>
        
        <!-- Highlighted T -->
        <text x="140" y="40" font-family="Arial, sans-serif" font-size="32" font-weight="bold" fill="#ff6b35">
            T
        </text>
        
        <!-- Decorative Element -->
        <path d="M25 45 L175 45" stroke="#ff6b35" stroke-width="2" />
    </svg>
</div>

<style>
.logo-container {
    display: inline-block;
    padding: 10px;
}

.logo-container svg {
    transition: transform 0.3s ease;
}

.logo-container:hover svg {
    transform: scale(1.05);
}
</style>
