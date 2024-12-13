// Navigation Menu Implementation
function initNavigationMenu() {
    // Element selections with null checks
    const menuToggle = document.getElementById('menuToggle');
    const navbar = document.querySelector('.navbar');
    const dropdownParents = document.querySelectorAll('.dropdown-parent');
    let isMenuOpen = false;

    // Validate essential elements
    if (!menuToggle || !navbar) {
        console.error('Required navigation elements not found');
        return;
    }

    // Toggle menu function with animation frame for better performance
    function toggleMenu() {
        requestAnimationFrame(() => {
            isMenuOpen = !isMenuOpen;
            menuToggle.classList.toggle('active');
            navbar.classList.toggle('active');
            document.body.style.overflow = isMenuOpen ? 'hidden' : '';
        });
    }

    // Close all dropdowns function
    function closeAllDropdowns(exceptParent = null) {
        dropdownParents.forEach(parent => {
            if (parent !== exceptParent) {
                parent.classList.remove('active');
            }
        });
    }

    // Handle dropdown toggle
    function handleDropdownToggle(parent, event) {
        event.preventDefault();
        event.stopPropagation();
        
        const wasActive = parent.classList.contains('active');
        closeAllDropdowns(parent);
        
        if (!wasActive) {
            parent.classList.add('active');
        } else {
            parent.classList.remove('active');
        }
    }

    // Event Listeners
    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMenu();
    });

    // Dropdown event listeners
    dropdownParents.forEach(parent => {
        const link = parent.querySelector('a');
        if (link) {
            link.addEventListener('click', (e) => handleDropdownToggle(parent, e));
        }
    });

    // Close menu on outside click
    document.addEventListener('click', (e) => {
        const target = e.target;
        
        // Check if click is outside navbar and menu is open
        if (isMenuOpen && 
            navbar && 
            !navbar.contains(target) && 
            !menuToggle.contains(target)) {
            toggleMenu();
        }

        // Close dropdowns if click is outside any dropdown
        if (!Array.from(dropdownParents).some(parent => parent.contains(target))) {
            closeAllDropdowns();
        }
    });

    // Handle keyboard navigation
    document.addEventListener('keydown', (e) => {
        // Close menu and dropdowns on ESC
        if (e.key === 'Escape') {
            if (isMenuOpen) {
                toggleMenu();
            }
            closeAllDropdowns();
        }

        // Handle tab navigation
        if (e.key === 'Tab' && isMenuOpen) {
            const focusableElements = navbar.querySelectorAll(
                'a[href], button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (e.shiftKey && document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            } else if (!e.shiftKey && document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        }
    });

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth > 768 && isMenuOpen) {
                toggleMenu();
                closeAllDropdowns();
            }
        }, 250);
    });
}

// Initialize when DOM is fully loaded
document.addEventListener('DOMContentLoaded', initNavigationMenu);
