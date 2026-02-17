/**
 * Mobile Home Page Enhancements
 * Makes the home page feel like a mobile app
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        // Only run on mobile devices
        if (window.innerWidth > 767) return;

        // 1. Smooth scroll for book card sections
        enableSmoothScroll();

        // 2. Touch gesture enhancements
        enableTouchGestures();

        // 3. Scroll indicators
        addScrollIndicators();

        // 4. Lazy load animations
        enableScrollAnimations();

        // 5. Prevent zoom on double tap
        preventDoubleTapZoom();
    }

    /**
     * Enable smooth horizontal scrolling for book cards
     * Let native scrolling handle it - just ensure containers are scrollable
     */
    function enableSmoothScroll() {
        // Remove custom touch handling - let native browser scrolling work
        // The CSS already handles overflow-x: auto and -webkit-overflow-scrolling: touch
        const bookCards = document.querySelectorAll('.book-cards');
        
        bookCards.forEach(container => {
            // Ensure the container allows native scrolling
            container.style.overflowX = 'auto';
            container.style.webkitOverflowScrolling = 'touch';
        });
    }

    /**
     * Add touch gesture enhancements
     */
    function enableTouchGestures() {
        // Add swipe detection for better UX
        const sections = document.querySelectorAll('.section');
        
        sections.forEach(section => {
            let touchStartY = 0;
            let touchEndY = 0;

            section.addEventListener('touchstart', (e) => {
                touchStartY = e.changedTouches[0].screenY;
            });

            section.addEventListener('touchend', (e) => {
                touchEndY = e.changedTouches[0].screenY;
                handleSwipe(section);
            });

            function handleSwipe(element) {
                const swipeThreshold = 50;
                const diff = touchStartY - touchEndY;

                if (Math.abs(diff) > swipeThreshold) {
                    // Add subtle feedback
                    element.style.transition = 'transform 0.2s';
                    element.style.transform = diff > 0 ? 'translateY(-5px)' : 'translateY(5px)';
                    
                    setTimeout(() => {
                        element.style.transform = '';
                    }, 200);
                }
            }
        });
    }

    /**
     * Add scroll indicators to book card sections
     */
    function addScrollIndicators() {
        const bookCards = document.querySelectorAll('.book-cards');
        
        bookCards.forEach(container => {
            const slide = container.querySelector('.book-card-slide');
            if (!slide) return;

            function checkScroll() {
                const isScrollable = slide.scrollWidth > slide.clientWidth;
                const isAtEnd = slide.scrollLeft + slide.clientWidth >= slide.scrollWidth - 10;
                
                if (isScrollable && !isAtEnd) {
                    container.classList.add('scrollable');
                } else {
                    container.classList.remove('scrollable');
                }
            }

            slide.addEventListener('scroll', checkScroll);
            checkScroll(); // Initial check
        });
    }

    /**
     * Enable scroll-triggered animations
     */
    function enableScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe sections
        document.querySelectorAll('.section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(section);
        });
    }

    /**
     * Prevent zoom on double tap (iOS) - but only on non-scrollable elements
     */
    function preventDoubleTapZoom() {
        let lastTouchEnd = 0;
        
        document.addEventListener('touchend', (e) => {
            // Don't prevent default on scrollable containers
            const isScrollable = e.target.closest('.book-cards');
            if (isScrollable) {
                return; // Let scrolling work normally
            }
            
            const now = Date.now();
            if (now - lastTouchEnd <= 300) {
                e.preventDefault();
            }
            lastTouchEnd = now;
        }, false);
    }

    // Handle orientation change
    window.addEventListener('orientationchange', () => {
        setTimeout(() => {
            // Recalculate scroll indicators
            document.querySelectorAll('.book-cards').forEach(container => {
                const slide = container.querySelector('.book-card-slide');
                if (slide) {
                    const isScrollable = slide.scrollWidth > slide.clientWidth;
                    if (isScrollable) {
                        container.classList.add('scrollable');
                    }
                }
            });
        }, 100);
    });
})();
