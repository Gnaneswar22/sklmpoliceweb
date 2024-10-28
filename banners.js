
let currentIndex = 0;
const banners = document.querySelectorAll('.image-container > div');
const totalBanners = banners.length;

function showBanner(index) {
    banners.forEach((banner, i) => {
        const textBox = banner.querySelector('.text-box');
        const backgroundImage = banner.querySelector('.background-image');
        banner.classList.remove('active');
        textBox.classList.remove('animate'); // Remove animation class
        if (i === index) {
            banner.classList.add('active');
            // Restart the animation
            void textBox.offsetWidth; // Trigger reflow
            textBox.classList.add('animate'); // Add animation class back
        }
    });
}

document.querySelector('.nav-button.left').addEventListener('click', () => {
    currentIndex = (currentIndex === 0) ? totalBanners - 1 : currentIndex - 1;
    showBanner(currentIndex);
});

document.querySelector('.nav-button.right').addEventListener('click', () => {
    currentIndex = (currentIndex === totalBanners - 1) ? 0 : currentIndex + 1;
    showBanner(currentIndex);
});

showBanner(currentIndex); // Show the first banner initially