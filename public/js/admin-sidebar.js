// Sidebar dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // For sidebar dropdowns - ensure they take full width and stay visible when clicked
    
    // Apply styles to make sure submenus display at full width
    const sidebarSubmenuContainers = document.querySelectorAll('.sidebar-submenu');
    sidebarSubmenuContainers.forEach(submenu => {
        submenu.style.width = '100%';
        
        // Apply styles to all child elements
        const childItems = submenu.querySelectorAll('.nav-item, .nav-link');
        childItems.forEach(item => {
            item.style.width = '100%';
        });
    });
    
    // Fix for dropdown toggles - ensure they stay visible and don't disappear when clicked
    const dropdownToggles = document.querySelectorAll('.sidebar .has-submenu > .nav-link');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Get target submenu
            const targetId = this.getAttribute('href');
            const targetSubmenu = document.querySelector(targetId);
            
            if (targetSubmenu) {
                // Toggle classes manually to ensure proper display
                if (this.classList.contains('collapsed')) {
                    this.classList.remove('collapsed');
                    this.setAttribute('aria-expanded', 'true');
                    targetSubmenu.classList.add('show');
                } else {
                    this.classList.add('collapsed');
                    this.setAttribute('aria-expanded', 'false');
                    targetSubmenu.classList.remove('show');
                }
            }
        });
    });
    
    // Highlight current page in the sidebar
    const currentPath = window.location.pathname;
    
    // Clear any existing active classes first
    const allNavLinks = document.querySelectorAll('.sidebar .nav-link');
    allNavLinks.forEach(link => {
        link.classList.remove('active');
    });
    
    // Check all sidebar links to find current page
    const sidebarLinks = document.querySelectorAll('.sidebar .nav-link[href]');
    let activeFound = false;
    
    sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && (href === currentPath || currentPath.includes(href)) && href !== '/' && !href.startsWith('#')) {
            // Mark this link as active
            link.classList.add('active');
            activeFound = true;
            
            // If inside a submenu, expand the submenu
            const parentSubmenu = link.closest('.sidebar-submenu');
            if (parentSubmenu) {
                parentSubmenu.classList.add('show');
                
                // Also update the toggle button
                const submenuId = parentSubmenu.id;
                const parentToggle = document.querySelector(`[href="#${submenuId}"]`);
                if (parentToggle) {
                    parentToggle.classList.remove('collapsed');
                    parentToggle.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });
    
    // Add a class to the sidebar to ensure it extends to the bottom
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.add('full-height');
    }
});