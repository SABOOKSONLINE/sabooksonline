<?php
// scroll-apple.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Smooth Scroll Showcase</title>

  <style>
    * {
      box-sizing: border-box;
    }

    section {
      opacity: 0;
      animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    section:nth-child(1) {
      animation-delay: 0.1s;
    }

    section:nth-child(2) {
      animation-delay: 0.3s;
    }

    /* Apple-style fade-in animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* --- Download Buttons --- */
    .download-buttons {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 40px;
      opacity: 0;
      animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards;
    }

    .download-buttons a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 14px 24px;
      border-radius: 12px;
      text-decoration: none;
      color: #000;
      font-weight: 600;
      font-size: 0.95rem;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
    }

    .download-buttons a::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(0, 0, 0, 0.05);
      transform: translate(-50%, -50%);
      transition: width 0.6s ease, height 0.6s ease;
    }

    .download-buttons a:hover::before {
      width: 300px;
      height: 300px;
    }

    .download-buttons a:hover {
      transform: translateY(-2px) scale(1.02);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .download-buttons a:active {
      transform: translateY(0) scale(0.98);
    }

    /* --- Mockup Images --- */
    .mockups {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 40px;
      padding: 40px 20px 60px 20px;
      perspective: 1000px;
    }

    .mockup {
      width: 230px;
      background: #f5f5f7;
      border-radius: 32px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(0, 0, 0, 0.04);
      overflow: hidden;
      transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
      opacity: 0;
      position: relative;
      will-change: transform, opacity;
    }

    /* Initial states - coming from different directions */
    .mockup.scroll-from-bottom {
      transform: translateY(80px) scale(0.9);
    }

    .mockup.scroll-from-left {
      transform: translateX(-80px) scale(0.9);
    }

    .mockup.scroll-from-right {
      transform: translateX(80px) scale(0.9);
    }

    /* Animated state when scrolled into view */
    .mockup.scroll-animated {
      opacity: 1;
      transform: translate(0, 0) scale(1);
    }

    /* Subtle floating animation - starts after scroll animation completes */
    @keyframes float {
      0%, 100% {
        transform: translate(0, 0) scale(1) rotateY(0deg);
      }
      50% {
        transform: translate(0, -8px) scale(1) rotateY(2deg);
      }
    }

    /* Apply floating animation after scroll animation completes */
    .mockup.animated {
      animation: float 6s ease-in-out infinite;
    }

    .mockup:nth-child(1).animated {
      animation-delay: 0s;
    }

    .mockup:nth-child(2).animated {
      animation-delay: 0.2s;
    }

    .mockup:nth-child(3).animated {
      animation-delay: 0.4s;
    }

    .mockup:nth-child(4).animated {
      animation-delay: 0.6s;
    }

    .mockup img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      background: #f5f5f7;
      transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
      display: block;
    }

    /* Apple-style hover effects */
    .mockup.scroll-animated:hover {
      transform: translateY(-12px) scale(1.05) rotateY(5deg) !important;
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.1);
      animation-play-state: paused !important;
    }

    .mockup.scroll-animated:hover img {
      transform: scale(1.02);
    }

    .mockup.animated:hover {
      animation: none !important;
    }

    /* Parallax effect on scroll */
    .mockup[data-scroll] {
      transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .mockups {
        gap: 30px;
        padding: 30px 15px 50px 15px;
      }

      .mockup {
        width: 180px;
        border-radius: 24px;
      }

      .download-buttons {
        gap: 12px;
      }

      .download-buttons a {
        padding: 12px 20px;
        font-size: 0.875rem;
      }
    }

    @media (max-width: 480px) {
      .mockup {
        width: 150px;
      }
    }

    /* Smooth scroll behavior */
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body>

  <section>
    <div class="container text-center">
      <h1>Read. Listen. Discover.</h1>
      <p>Explore books, magazines, newspapers, academic texts, and audiobooks — all in one intuitive app designed for your enjoyment.</p>

      <!-- Download Buttons -->
      <div class="download-buttons">
        <a href="https://apps.apple.com/us/app/sa-books-online/id6746923717?platform=iphone" class="ios">
          <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="iOS" width="40" />
          Download on iOS
        </a>
        <a href="https://play.google.com/store/apps/details?id=com.anonymous.sabooksmobile" class="android">
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Android_robot.svg" alt="Android" width="40" />
        Download on Android
      </a>
      </div>
    </div>
  </section>

  <section>
    <div class="mockups">
      <div class="mockup scroll-from-left" data-scroll><img src="https://sabooksonline.co.za/cms-data/mobile/home.png" alt="Book 1" /></div>
      <div class="mockup scroll-from-bottom" data-scroll><img src="https://sabooksonline.co.za/cms-data/mobile/education.png" alt="Book 2" /></div>
      <div class="mockup scroll-from-right" data-scroll><img src="https://sabooksonline.co.za/cms-data/mobile/library.png" alt="Book 3" /></div>
      <div class="mockup scroll-from-bottom" data-scroll><img src="https://sabooksonline.co.za/cms-data/mobile/media.png" alt="Book 4" /></div>
    </div>
  </section>

  <script>
    // Apple-style parallax and scroll animations
    (function() {
      'use strict';

      // Subtle parallax effect on scroll (only for animated elements, disabled during float animation)
      function initParallax() {
        const mockups = document.querySelectorAll('.mockup.scroll-animated');
        
        function updateParallax() {
          mockups.forEach((mockup) => {
            // Skip parallax if floating animation is active (let CSS handle it)
            if (mockup.classList.contains('animated') && !mockup.matches(':hover')) {
              return;
            }
            
            const rect = mockup.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            const elementTop = rect.top;
            const elementCenter = elementTop + rect.height / 2;
            const windowCenter = windowHeight / 2;
            
            // Calculate parallax offset (subtle effect)
            const distance = elementCenter - windowCenter;
            const parallaxOffset = distance * 0.03;
            
            // Apply subtle parallax (only when in viewport and animated, not hovering)
            if (rect.top < windowHeight && rect.bottom > 0 && mockup.classList.contains('scroll-animated') && !mockup.matches(':hover')) {
              mockup.style.transform = `translate(0, ${parallaxOffset}px) scale(1)`;
            }
          });
        }

        // Throttle scroll events for performance
        let ticking = false;
        window.addEventListener('scroll', function() {
          if (!ticking) {
            window.requestAnimationFrame(function() {
              updateParallax();
              ticking = false;
            });
            ticking = true;
          }
        }, { passive: true });
      }

      // Intersection Observer for scroll-triggered animations from different directions
      function initScrollAnimations() {
        const observerOptions = {
          threshold: 0.2,
          rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
          entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
              // Add animated class to trigger the scroll-in animation
              entry.target.classList.add('scroll-animated');
              
              // Add floating animation after scroll animation completes
              setTimeout(() => {
                entry.target.classList.add('animated');
              }, 900);
              
              observer.unobserve(entry.target);
            }
          });
        }, observerOptions);

        // Observe all mockups
        document.querySelectorAll('.mockup').forEach(mockup => {
          observer.observe(mockup);
        });
      }

      // Initialize when DOM is ready
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
          initParallax();
          initScrollAnimations();
        });
      } else {
        initParallax();
        initScrollAnimations();
      }

      // Smooth reveal on page load
      window.addEventListener('load', function() {
        document.body.style.opacity = '1';
      });
    })();
  </script>

</body>

</html>