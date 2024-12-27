// Initial declarations
const galleryFolders = document.querySelectorAll('.folder');
const foldersContainer = document.querySelector('.folders');
const backButton = document.querySelector('.back-button');
const mediaGrids = document.querySelectorAll('.media-grid');
const title = document.querySelector('.title');
const lightbox = document.querySelector('.lightbox');
const lightboxContent = document.querySelector('.lightbox-content');
let currentMediaItems = [];
let currentIndex = 0;

// Global navigation functions
window.navigateMedia = function(direction) {
    currentIndex = (currentIndex + direction + currentMediaItems.length) % currentMediaItems.length;
    const nextItem = currentMediaItems[currentIndex];
    if (nextItem) {
        openLightbox(nextItem);
    }
};

function openLightbox(mediaItem) {
    const isVideo = mediaItem.classList.contains('video');
    const mediaElement = isVideo ? 
        mediaItem.querySelector('video').cloneNode(true) : 
        mediaItem.querySelector('img').cloneNode(true);

    // Clear previous content
    lightboxContent.innerHTML = '';
    lightboxContent.appendChild(mediaElement);
    
    // Add navigation buttons with direct onclick handlers
    const nav = document.createElement('div');
    nav.className = 'lightbox-nav';
    nav.innerHTML = `
        <button class="prev-btn" onclick="navigateMedia(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="next-btn" onclick="navigateMedia(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
    `;
    lightboxContent.appendChild(nav);

    // Add close button
    const closeBtn = document.createElement('button');
    closeBtn.className = 'lightbox-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = closeLightbox;
    lightboxContent.appendChild(closeBtn);

    // Show lightbox
    lightbox.classList.add('active');

    // Handle video autoplay
    if (isVideo && mediaElement.paused) {
        mediaElement.play();
    }

    updateNavigation();
}

function closeLightbox() {
    lightbox.classList.remove('active');
    const video = lightboxContent.querySelector('video');
    if (video) {
        video.pause();
    }
}

function updateNavigation() {
    const prevBtn = lightboxContent.querySelector('.prev-btn');
    const nextBtn = lightboxContent.querySelector('.next-btn');
    
    if (prevBtn && nextBtn) {
        prevBtn.style.display = currentIndex > 0 ? 'block' : 'none';
        nextBtn.style.display = currentIndex < currentMediaItems.length - 1 ? 'block' : 'none';
    }
}

// Initialize lightbox for media items
function initializeLightbox() {
    const mediaItems = document.querySelectorAll('.media-item');
    mediaItems.forEach((item, index) => {
        item.onclick = () => {
            currentMediaItems = Array.from(item.parentElement.querySelectorAll('.media-item'));
            currentIndex = index;
            openLightbox(item);
        };
    });
}

// Folder click handlers
galleryFolders.forEach(folder => {
    folder.addEventListener('click', () => {
        const folderName = folder.getAttribute('data-folder');
        const mediaGrid = document.getElementById(`${folderName}Grid`);
        
        title.textContent = folder.querySelector('h3').textContent;
        foldersContainer.style.display = 'none';
        mediaGrid.style.display = 'grid';
        backButton.style.display = 'block';

        const items = mediaGrid.querySelectorAll('.media-item');
        items.forEach((item, index) => {
            item.style.animation = `scaleIn 0.5s ease ${index * 0.1}s both`;
        });

        setTimeout(initializeLightbox, 300);
    });
});

// Back button handler
backButton.addEventListener('click', () => {
    title.textContent = 'Media Gallery';
    mediaGrids.forEach(grid => {
        grid.style.display = 'none';
    });
    foldersContainer.style.display = 'grid';
    backButton.style.display = 'none';
});

// Lightbox click outside to close
lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) {
        closeLightbox();
    }
});

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (!lightbox.classList.contains('active')) return;
    
    switch(e.key) {
        case 'Escape':
            closeLightbox();
            break;
        case 'ArrowLeft':
            navigateMedia(-1);
            break;
        case 'ArrowRight':
            navigateMedia(1);
            break;
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', initializeLightbox);

document.addEventListener('DOMContentLoaded', function() {
    initializeTopOfficers();
    initializeRegularOfficers();
});

function initializeTopOfficers() {
    const topOfficerCards = document.querySelectorAll('.top-officer-card');
    
    topOfficerCards.forEach((card, index) => {
        const name = card.querySelector('h3').textContent;
        const role = card.querySelector('p').textContent;
        const imgSrc = card.querySelector('img').src;

        card.addEventListener('click', () => {
            showOfficerProfile({
                name,
                role,
                imgSrc,
                isTopOfficer: true
            });
        });
    });
}

function initializeRegularOfficers() {
    const officerCards = document.querySelectorAll('.officer-card');
    
    officerCards.forEach(card => {
        const name = card.querySelector('.officer-name').textContent;
        const station = card.querySelector('.officer-station').textContent;
        const imgSrc = card.querySelector('.officer-image').src;

        card.addEventListener('click', () => {
            showOfficerProfile({
                name,
                role: station,
                imgSrc,
                isTopOfficer: false
            });
        });
    });
}

function showOfficerProfile(officer) {
    const modal = document.createElement('div');
    modal.className = 'profile-modal';
    
    const contactEmail = generateEmail(officer.name);
    const phoneNumber = generatePhoneNumber();

    modal.innerHTML = `
        <div class="profile-content">
            <button class="close-modal" aria-label="Close profile">&times;</button>
            <div class="profile-header">
                <div class="profile-image">
                    <img src="${officer.imgSrc}" alt="${officer.name}" loading="lazy">
                </div>
                <h2>${officer.name}</h2>
                <p>${officer.role}</p>
            </div>
            <div class="profile-details">
                <div class="detail-item">
                    <i class="fas fa-phone"></i>
                    <p>${phoneNumber}</p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-envelope"></i>
                    <p>${contactEmail}</p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <p>Office Hours: 9:00 AM - 5:00 PM</p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>${officer.role}</p>
                </div>
                ${officer.isTopOfficer ? `
                <div class="detail-item">
                    <i class="fas fa-id-badge"></i>
                    <p>Senior Official</p>
                </div>
                ` : ''}
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    
    // Activate modal with animation
    requestAnimationFrame(() => {
        modal.classList.add('active');
    });

    // Close modal functionality
    const closeBtn = modal.querySelector('.close-modal');
    const closeModal = () => {
        modal.classList.remove('active');
        setTimeout(() => modal.remove(), 300);
    };

    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    
    // Keyboard accessibility
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.parentElement) closeModal();
    });
}

// Helper function to generate email
function generateEmail(name) {
    return `${name.toLowerCase().replace(/\s+/g, '.')}@police.gov.in`;
}

// Helper function to generate phone number
function generatePhoneNumber() {
    return '+91 9876543210'; // Example phone number
}

// Optional: Add loading animation for images
document.querySelectorAll('img').forEach(img => {
    img.addEventListener('load', function() {
        this.style.opacity = '1';
    });
    img.style.opacity = '0';
    img.style.transition = 'opacity 0.3s ease';
});

// Optional: Add scroll animation for cards
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.officer-card, .top-officer-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'all 0.5s ease';
    observer.observe(card);
});
