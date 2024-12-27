


        

                // 1. Hero Section Slider Implementation
                document.addEventListener('DOMContentLoaded', () => {
                    const sliderContainer = document.querySelector('.slider-container');
                    const slides = document.querySelectorAll('.slide');
                    const prevBtn = document.querySelector('.prev');
                    const nextBtn = document.querySelector('.next');
                    const TOTAL_SLIDES = 5; // Fixed number of slides
                    let currentSlide = 0;
                    let isAnimating = false;
                    let autoPlayInterval;

                    // Update clock function with current time
                    function updateClock() {
                        const now = new Date();
                        const hours = String(now.getHours()).padStart(2, '0');
                        const minutes = String(now.getMinutes()).padStart(2, '0');
                        const seconds = String(now.getSeconds()).padStart(2, '0');
                        const dateStr = now.toISOString().split('T')[0];

                        document.querySelectorAll('.digital-clock').forEach(clock => {
                            const timeElements = {
                                hours: clock.querySelector('.hours'),
                                minutes: clock.querySelector('.minutes'),
                                seconds: clock.querySelector('.seconds'),
                                date: clock.querySelector('.date')
                            };

                            if (timeElements.hours) timeElements.hours.textContent = hours;
                            if (timeElements.minutes) timeElements.minutes.textContent = minutes;
                            if (timeElements.seconds) timeElements.seconds.textContent = seconds;
                            if (timeElements.date) timeElements.date.textContent = dateStr;
                        });
                    }

                    // Show specific slide
                    function showSlide(index) {
                        if (isAnimating || index < 0 || index >= TOTAL_SLIDES) return;
                        isAnimating = true;

                        // Hide all slides first
                        slides.forEach(slide => {
                            slide.classList.remove('active');
                            slide.style.display = 'none';
                        });

                        // Show and animate current slide
                        slides[index].style.display = 'block';
                        slides[index].classList.add('active');
                        resetAnimations(slides[index]);

                        currentSlide = index;

                        setTimeout(() => {
                            isAnimating = false;
                        }, 1500);
                    }

                    // Navigation functions
                    function nextSlide() {
                        const nextIndex = (currentSlide + 1) % TOTAL_SLIDES;
                        showSlide(nextIndex);
                        resetAutoPlay();
                    }

                    function prevSlide() {
                        const prevIndex = (currentSlide - 1 + TOTAL_SLIDES) % TOTAL_SLIDES;
                        showSlide(prevIndex);
                        resetAutoPlay();
                    }

                    // Reset autoplay timer
                    function resetAutoPlay() {
                        clearInterval(autoPlayInterval);
                        autoPlayInterval = setInterval(nextSlide, 5000);
                    }

                    // Reset slide animations
                    function resetAnimations(slide) {
                        if (!slide) return;

                        const elements = {
                            overlay1: slide.querySelector('.overlay-1'),
                            overlay2: slide.querySelector('.overlay-2'),
                            h1: slide.querySelector('h1'),
                            p: slide.querySelector('p'),
                            clock: slide.querySelector('.digital-clock')
                        };

                        // Reset animations
                        Object.values(elements).forEach(element => {
                            if (element) {
                                element.style.animation = 'none';
                                element.offsetHeight; // Force reflow
                            }
                        });

                        // Apply animations
                        requestAnimationFrame(() => {
                            if (elements.overlay1) {
                                elements.overlay1.style.animation = 'slideInOverlay1 1.5s forwards';
                            }
                            if (elements.overlay2) {
                                elements.overlay2.style.animation = 'slideInOverlay2 1.5s forwards';
                            }
                            if (elements.h1) {
                                elements.h1.style.animation = 'slideInText 1s forwards';
                                elements.h1.style.animationDelay = '1.2s';
                            }
                            if (elements.p) {
                                elements.p.style.animation = 'slideInText 1s forwards';
                                elements.p.style.animationDelay = '1.4s';
                            }
                            if (elements.clock) {
                                elements.clock.style.animation = 'fadeIn 1s forwards';
                                elements.clock.style.animationDelay = '1.5s';
                            }
                        });
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        const slides = document.querySelectorAll('.slider-item');
                        const dots = document.querySelectorAll('.dot');
                        const prevBtn = document.querySelector('.prev');
                        const nextBtn = document.querySelector('.next');
                        let currentSlide = 0;
                        let slideInterval;
                    
                        function showSlide(index) {
                            slides.forEach(slide => {
                                slide.classList.remove('active');
                                slide.style.display = 'none';
                            });
                            dots.forEach(dot => dot.classList.remove('active'));
                            
                            slides[index].classList.add('active');
                            slides[index].style.display = 'block';
                            dots[index].classList.add('active');
                        }
                    
                        function nextSlide() {
                            currentSlide = (currentSlide + 1) % slides.length;
                            showSlide(currentSlide);
                        }
                    
                        function prevSlide() {
                            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                            showSlide(currentSlide);
                        }
                    
                        function startSlideShow() {
                            if (slideInterval) {
                                clearInterval(slideInterval);
                            }
                            slideInterval = setInterval(nextSlide, 3000);
                        }
                    
                        function stopSlideShow() {
                            clearInterval(slideInterval);
                        }
                    
                        // Event Listeners
                        prevBtn.addEventListener('click', () => {
                            prevSlide();
                            stopSlideShow();
                            startSlideShow();
                        });
                    
                        nextBtn.addEventListener('click', () => {
                            nextSlide();
                            stopSlideShow();
                            startSlideShow();
                        });
                    
                        dots.forEach((dot, index) => {
                            dot.addEventListener('click', () => {
                                currentSlide = index;
                                showSlide(currentSlide);
                                stopSlideShow();
                                startSlideShow();
                            });
                        });
                    
                        // Initialize
                        showSlide(currentSlide);
                        startSlideShow();
                    });
                    
                    // Initialize slider
                    function initSlider() {
                        // Hide all slides initially
                        slides.forEach(slide => {
                            slide.style.display = 'none';
                        });

                        // Show first slide
                        showSlide(0);
                        updateClock();

                        // Set up intervals
                        setInterval(updateClock, 1000);
                        resetAutoPlay();

                        // Event listeners
                        prevBtn.addEventListener('click', prevSlide);
                        nextBtn.addEventListener('click', nextSlide);

                        // Keyboard navigation
                        document.addEventListener('keydown', (e) => {
                            if (e.key === 'ArrowLeft') prevSlide();
                            if (e.key === 'ArrowRight') nextSlide();
                        });

                        // Pause autoplay on hover
                        sliderContainer.addEventListener('mouseenter', () => {
                            clearInterval(autoPlayInterval);
                        });

                        sliderContainer.addEventListener('mouseleave', resetAutoPlay);
                    }

                    // Start the slider
                    initSlider();
                });

                document.querySelector('.read-more').addEventListener('mouseenter', function() {
    // Pause any ongoing animations or transitions
    document.querySelectorAll('.slide, .slide *').forEach(element => {
        element.style.animationPlayState = 'paused';
        element.style.transitionDelay = '0s';
    });
});

document.querySelector('.read-more').addEventListener('mouseleave', function() {
    // Resume animations
    document.querySelectorAll('.slide, .slide *').forEach(element => {
        element.style.animationPlayState = 'running';
        element.style.transitionDelay = '';
    });
});

const slideInterval = 8000; // 8 seconds

function startSlideShow() {
    setInterval(() => {
        // Your slide transition code here
    }, slideInterval);
}

// Start the slideshow
startSlideShow();

// Pause on button hover
document.querySelector('.read-more').addEventListener('mouseenter', function() {
    document.querySelectorAll('.slide, .slide *').forEach(element => {
        element.style.animationPlayState = 'paused';
    });
});

// Resume on button mouse leave
document.querySelector('.read-more').addEventListener('mouseleave', function() {
    document.querySelectorAll('.slide, .slide *').forEach(element => {
        element.style.animationPlayState = 'running';
    });
});



                // 2. Emergency Helpline Implementation
                function initEmergencyHelpline() {
                    const helplineContent = document.querySelector('.helpline-scroll');

                    if (helplineContent) {
                        helplineContent.addEventListener('mouseenter', function () {
                            this.style.animationPlayState = 'paused';
                        });

                        helplineContent.addEventListener('mouseleave', function () {
                            this.style.animationPlayState = 'running';
                        });
                    }
                }

                // 3. Press Ticker Implementation
                function initPressTicker() {
                    const newsData = [
                        {
                            title: "Latest Police Updates - December 08, 2024",
                            date: "2024-12-08",
                            link: "#"
                        },
                        // Add more news items as needed
                    ];

                    const pressData = [
                        {
                            title: "Police Department Press Release",
                            date: "2024-12-08",
                            link: "#"
                        },
                        // Add more press items as needed
                    ];

                    function createTickerItem(data, isNews = true) {
                        const item = document.createElement('li');
                        item.className = isNews ? 'news-item' : 'press-item';
                        item.onclick = () => showNews(item);

                        const dateSpan = document.createElement('span');
                        dateSpan.className = isNews ? 'news-date' : 'press-date';
                        dateSpan.textContent = new Date(data.date).toLocaleDateString();

                        const titleSpan = document.createElement('span');
                        titleSpan.className = isNews ? 'news-title' : 'press-title';
                        titleSpan.textContent = data.title;

                        const contentDiv = document.createElement('div');
                        contentDiv.className = 'news-content';
                        contentDiv.style.display = 'none';
                        contentDiv.innerHTML = `<p>Detailed content for ${data.title}</p>`;

                        item.appendChild(dateSpan);
                        item.appendChild(titleSpan);
                        item.appendChild(contentDiv);
                        return item;
                    }

                    const newsList = document.querySelector('.news-list');
                    const pressList = document.querySelector('.press-list');

                    if (newsList) {
                        newsData.forEach(data => newsList.appendChild(createTickerItem(data, true)));
                    }
                    if (pressList) {
                        pressData.forEach(data => pressList.appendChild(createTickerItem(data, false)));
                    }
                }

                // 4. Navigation Menu Implementation
                document.addEventListener('DOMContentLoaded', function() {
                    const mobileMenu = document.getElementById('mobile-menu');
                    const navList = document.getElementById('nav-list');
                    const dropdownParents = document.querySelectorAll('.dropdown-parent');
                
                    // Mobile menu toggle
                    mobileMenu.addEventListener('click', function() {
                        this.classList.toggle('active');
                        navList.classList.toggle('active');
                    });
                
                    // Handle dropdown toggles on mobile
                    dropdownParents.forEach(parent => {
                        const link = parent.querySelector('a');
                        link.addEventListener('click', function(e) {
                            if (window.innerWidth <= 768) {
                                e.preventDefault();
                                const currentDropdown = this.parentElement;
                                
                                // Close other dropdowns
                                dropdownParents.forEach(otherParent => {
                                    if (otherParent !== currentDropdown) {
                                        otherParent.classList.remove('active');
                                    }
                                });
                
                                // Toggle current dropdown
                                currentDropdown.classList.toggle('active');
                            }
                        });
                    });
                
                    // Close menu when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!e.target.closest('.navbar')) {
                            navList.classList.remove('active');
                            mobileMenu.classList.remove('active');
                            dropdownParents.forEach(parent => {
                                parent.classList.remove('active');
                            });
                        }
                    });
                
                    // Close menu when window is resized above mobile breakpoint
                    window.addEventListener('resize', function() {
                        if (window.innerWidth > 768) {
                            navList.classList.remove('active');
                            mobileMenu.classList.remove('active');
                            dropdownParents.forEach(parent => {
                                parent.classList.remove('active');
                            });
                        }
                    });
                });
                
                // 5. News Popup Implementation
                // Function to open popup
                function openPopup(title, date, content) {
                    const popup = document.getElementById('newsPopup');
                    const popupTitle = document.getElementById('popupTitle');
                    const popupDate = document.getElementById('popupDate');
                    const popupContent = document.getElementById('popupContent');

                    popupTitle.textContent = title;
                    popupDate.textContent = date;
                    popupContent.textContent = content;

                    popup.style.display = 'block';
                }

                // Function to close popup
                function closePopup() {
                    const popup = document.getElementById('newsPopup');
                    popup.style.display = 'none';
                }

                // Event listeners for news and press items
                document.querySelectorAll('.news-item, .press-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const title = item.querySelector('.news-title, .press-title').textContent;
                        const date = item.querySelector('.news-date, .press-date').textContent;
                        // You can add more content here or fetch it from a database
                        const content = "Detailed content for " + title;
                        openPopup(title, date, content);
                    });
                });

                // Close popup when clicking outside
                window.addEventListener('click', (event) => {
                    const popup = document.getElementById('newsPopup');
                    if (event.target === popup) {
                        closePopup();
                    }
                });

                // Close popup with Escape key
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closePopup();
                    }
                });
                //over 

                document.addEventListener('DOMContentLoaded', function () {
                    const carousel = document.querySelector('.carousel');
                    const cards = document.querySelectorAll('.carousel__card');
                    const dotsContainer = document.querySelector('.carousel__dots');
                    let currentIndex = 0;
                    let autoSlideTimer;

                    function getVisibleSlides() {
                        if (window.innerWidth >= 1024) return 3;
                        if (window.innerWidth >= 768) return 2;
                        return 1;
                    }

                    function updateCarousel() {
                        const slideWidth = carousel.clientWidth / getVisibleSlides();
                        const offset = -currentIndex * slideWidth;
                        carousel.style.transform = `translateX(${offset}px)`;
                        updateDots();
                    }

                    function createDots() {
                        dotsContainer.innerHTML = '';
                        const totalDots = Math.ceil(cards.length / getVisibleSlides());

                        for (let i = 0; i < totalDots; i++) {
                            const dot = document.createElement('button');
                            dot.className = 'carousel__dot';
                            if (i === 0) dot.classList.add('active');
                            dot.addEventListener('click', () => goToSlide(i));
                            dotsContainer.appendChild(dot);
                        }
                    }

                    function updateDots() {
                        const dots = document.querySelectorAll('.carousel__dot');
                        dots.forEach((dot, index) => {
                            dot.classList.toggle('active', index === currentIndex);
                        });
                    }

                    function nextSlide() {
                        const maxIndex = Math.ceil(cards.length / getVisibleSlides()) - 1;
                        currentIndex = currentIndex >= maxIndex ? 0 : currentIndex + 1;
                        updateCarousel();
                    }

                    function goToSlide(index) {
                        currentIndex = index;
                        updateCarousel();
                        resetAutoSlide();
                    }

                    function startAutoSlide() {
                        stopAutoSlide();
                        autoSlideTimer = setInterval(nextSlide, 3000);
                    }

                    function stopAutoSlide() {
                        if (autoSlideTimer) clearInterval(autoSlideTimer);
                    }

                    function resetAutoSlide() {
                        stopAutoSlide();
                        startAutoSlide();
                    }

                    // Initialize
                    createDots();
                    startAutoSlide();

                    // Event Listeners
                    window.addEventListener('resize', () => {
                        createDots();
                        updateCarousel();
                    });

                    carousel.addEventListener('mouseenter', stopAutoSlide);
                    carousel.addEventListener('mouseleave', startAutoSlide);
                });

                //gallery section

                document.addEventListener('DOMContentLoaded', function () {
                    const gallery = document.querySelector('.gallery-grid');
                    const modal = document.querySelector('.modal');
                    const modalMedia = document.querySelector('.modal-media-container');
                    const modalCaption = document.querySelector('.modal-caption');
                    const closeBtn = document.querySelector('.modal-close');
                    const prevBtn = document.querySelector('.modal-nav.prev');
                    const nextBtn = document.querySelector('.modal-nav.next');
                    let currentIndex = 0;
                    const items = document.querySelectorAll('.gallery-item');

                    // Open Modal
                    function openModal(index) {
                        currentIndex = index;
                        updateModal();
                        modal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }

                    // Close Modal
                    function closeModal() {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                        modalMedia.innerHTML = '';
                    }

                    // Update Modal Content
                    function updateModal() {
                        const item = items[currentIndex];
                        const type = item.dataset.type;
                        const src = item.dataset.src;
                        const title = item.dataset.title;

                        modalMedia.innerHTML = '';
                        if (type === 'image') {
                            const img = document.createElement('img');
                            img.src = src;
                            img.alt = title;
                            modalMedia.appendChild(img);
                        } else if (type === 'video') {
                            const video = document.createElement('video');
                            video.src = src;
                            video.controls = true;
                            modalMedia.appendChild(video);
                        }

                        modalCaption.textContent = title;
                    }

                    // Event Listeners
                    gallery.addEventListener('click', function (e) {
                        const item = e.target.closest('.gallery-item');
                        if (item) {
                            const index = Array.from(items).indexOf(item);
                            openModal(index);
                        }
                    });

                    closeBtn.addEventListener('click', closeModal);

                    prevBtn.addEventListener('click', function () {
                        currentIndex = (currentIndex - 1 + items.length) % items.length;
                        updateModal();
                    });

                    nextBtn.addEventListener('click', function () {
                        currentIndex = (currentIndex + 1) % items.length;
                        updateModal();
                    });

                    // Close on outside click
                    modal.addEventListener('click', function (e) {
                        if (e.target === modal) {
                            closeModal();
                        }
                    });

                    // Keyboard Navigation
                    document.addEventListener('keydown', function (e) {
                        if (!modal.classList.contains('active')) return;

                        if (e.key === 'Escape') closeModal();
                        if (e.key === 'ArrowLeft') prevBtn.click();
                        if (e.key === 'ArrowRight') nextBtn.click();
                    });
                });
                
                //pdf in news
                function downloadPDF() {
                    // Add your PDF download logic here
                    const title = document.getElementById('popupTitle').textContent;
                    const date = document.getElementById('popupDate').textContent;
                    
                    // Example implementation
                    console.log(`Downloading PDF for: ${title} (${date})`);
                    
                    // You can trigger the PDF download here
                    // window.open('path_to_your_pdf.pdf', '_blank');
                }
                




                function openMedia(path, type, title) {
            const modal = document.getElementById('mediaModal');
            const mediaContainer = document.getElementById('mediaContainer');
            const mediaTitle = document.getElementById('mediaTitle');

            // Clear previous content
            mediaContainer.innerHTML = '';

            if (type === 'image') {
                const img = document.createElement('img');
                img.src = path;
                img.alt = title;
                mediaContainer.appendChild(img);
            } else if (type === 'video') {
                const video = document.createElement('video');
                video.controls = true;
                video.autoplay = true;
                const source = document.createElement('source');
                source.src = path;
                source.type = 'video/mp4';
                video.appendChild(source);
                mediaContainer.appendChild(video);
            }

            mediaTitle.textContent = title;
            modal.style.display = 'flex';
        }

        function closeMedia() {
            const modal = document.getElementById('mediaModal');
            modal.style.display = 'none';
        }
    
//footer section
document.addEventListener('DOMContentLoaded', function() {
    const menuTriggers = document.querySelectorAll('.footer-menu-trigger');
    
    menuTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Close all other open menus
            menuTriggers.forEach(otherTrigger => {
                if (otherTrigger !== trigger) {
                    otherTrigger.parentElement.classList.remove('active');
                }
            });
            
            // Toggle current menu
            const parentMenu = this.parentElement;
            parentMenu.classList.toggle('active');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.footer-menu')) {
            menuTriggers.forEach(trigger => {
                trigger.parentElement.classList.remove('active');
            });
        }
    });
});





           



