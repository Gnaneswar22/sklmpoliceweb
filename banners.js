document.addEventListener('DOMContentLoaded', () => {
    const banners = document.querySelectorAll('[class^="banner-"]');
    const leftBtn = document.querySelector('.left');
    const rightBtn = document.querySelector('.right');
    let currentIndex = 0;
    const autoSlideInterval = 5000; // 5 seconds
    let autoSlideTimer;

    // Function to show banner
    function showBanner(index) {
        // Remove active class from all banners
        banners.forEach(banner => {
            banner.classList.remove('active');
            const textBox = banner.querySelector('.text-box');
            textBox.classList.remove('animate');
        });

        // Add active class to current banner
        banners[index].classList.add('active');
        const textBox = banners[index].querySelector('.text-box');
        textBox.classList.add('animate');
    }

    // Function to show next banner
    function nextBanner() {
        currentIndex = (currentIndex + 1) % banners.length;
        showBanner(currentIndex);
    }

    // Function to show previous banner
    function prevBanner() {
        currentIndex = (currentIndex - 1 + banners.length) % banners.length;
        showBanner(currentIndex);
    }

    // Auto slide function
    function startAutoSlide() {
        stopAutoSlide();
        autoSlideTimer = setInterval(nextBanner, autoSlideInterval);
    }

    // Stop auto slide
    function stopAutoSlide() {
        if (autoSlideTimer) {
            clearInterval(autoSlideTimer);
        }
    }

    // Event listeners for buttons
    leftBtn.addEventListener('click', () => {
        prevBanner();
        startAutoSlide(); // Restart auto slide after manual navigation
    });

    rightBtn.addEventListener('click', () => {
        nextBanner();
        startAutoSlide(); // Restart auto slide after manual navigation
    });

    // Start auto slide on page load
    startAutoSlide();

    // Pause auto slide when hovering over the container
    const container = document.querySelector('.image-container');
    container.addEventListener('mouseenter', stopAutoSlide);
    container.addEventListener('mouseleave', startAutoSlide);
});
