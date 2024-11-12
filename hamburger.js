function toggleMenu() {
    const navUl = document.querySelector('nav ul');
    navUl.classList.toggle('show');

    // Close all submenus when closing the main menu
    if (!navUl.classList.contains('show')) {
        document.querySelectorAll('nav ul li').forEach(item => {
            item.classList.remove('active');
        });
    }
}

// Handle submenu toggles on mobile
document.addEventListener('DOMContentLoaded', function () {
    if (window.innerWidth <= 768) {
        const menuItems = document.querySelectorAll('nav ul li');

        menuItems.forEach(item => {
            if (item.querySelector('ul')) {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    this.classList.toggle('active');
                });
            }
        });
    }
});

// Close menu when clicking outside
document.addEventListener('click', function (e) {
    const nav = document.querySelector('nav');
    const hamburger = document.querySelector('.hamburger');

    if (!nav.contains(e.target) && !hamburger.contains(e.target)) {
        document.querySelector('nav ul').classList.remove('show');
        document.querySelectorAll('nav ul li').forEach(item => {
            item.classList.remove('active');
        });
    }
});