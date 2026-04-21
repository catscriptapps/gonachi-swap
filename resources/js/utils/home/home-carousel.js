// /resources/js/utils/home/home-carousel.js

export function initHomeCarousel(){
    const carousel = document.getElementById('hero-carousel');
    const dots = document.querySelectorAll('.carousel-dot');
    
    if (!carousel || dots.length === 0) return;

    let currentIndex = 0;
    const totalSlides = dots.length;
    let autoSlideInterval;

    const updateDots = (index) => {
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.style.width = '24px';
                dot.style.backgroundColor = '#fb923c'; // primary-400
                dot.style.opacity = '1';
            } else {
                dot.style.width = '8px';
                dot.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
                dot.style.opacity = '0.5';
            }
        });
    };

    const startAutoSlide = () => {
        autoSlideInterval = setInterval(() => {
            currentIndex = (currentIndex + 1) % totalSlides;
            carousel.scrollTo({
                left: carousel.offsetWidth * currentIndex,
                behavior: 'smooth'
            });
        }, 5000);
    };

    // Update index based on actual scroll position (handles manual swipes)
    carousel.addEventListener('scroll', () => {
        const index = Math.round(carousel.scrollLeft / carousel.offsetWidth);
        if (index !== currentIndex) {
            currentIndex = index;
            updateDots(currentIndex);
        }
    });

    // Pause on interaction
    carousel.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
    carousel.addEventListener('mouseleave', startAutoSlide);

    // Manual dot navigation
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            carousel.scrollTo({
                left: carousel.offsetWidth * i,
                behavior: 'smooth'
            });
        });
    });

    // Initialize
    updateDots(0);
    startAutoSlide();
}