<section class="section app-section" id="mobile" style="scroll-margin-top: 100px;">
  <div class="container">
    <?php renderSectionHeading("Read. Listen. Discover.", "Explore books, magazines, newspapers and audiobooks — all in one app.", "", "#", "center") ?>

    <div class="app-buttons text-center mt-4">
      <a href="https://apps.apple.com/us/app/sa-books-online/id6746923717?platform=iphone" class="app-btn app-btn-ios">
        <i class="fab fa-apple"></i>
        <div class="app-btn-text">
          <span class="app-btn-sub">Download on the</span>
          <span class="app-btn-main">App Store</span>
        </div>
      </a>
      <a href="https://play.google.com/store/apps/details?id=com.anonymous.sabooksmobile" class="app-btn app-btn-android">
        <i class="fab fa-google-play"></i>
        <div class="app-btn-text">
          <span class="app-btn-sub">Get it on</span>
          <span class="app-btn-main">Google Play</span>
        </div>
      </a>
    </div>
  </div>

  <!-- <div class="app-mockups">
    <div class="mockup scroll-from-left" data-scroll>
      <img src="https://sabooksonline.co.za/cms-data/mobile/home.png" alt="Home screen" />
    </div>
    <div class="mockup scroll-from-bottom" data-scroll>
      <img src="https://sabooksonline.co.za/cms-data/mobile/education.png" alt="Education screen" />
    </div>
    <div class="mockup scroll-from-right" data-scroll>
      <img src="https://sabooksonline.co.za/cms-data/mobile/library.png" alt="Library screen" />
    </div>
    <div class="mockup scroll-from-bottom" data-scroll>
      <img src="https://sabooksonline.co.za/cms-data/mobile/media.png" alt="Media screen" />
    </div>
  </div> -->
</section>

<a href="#mobile">
  <img style="width: 100%;" src="/public/images/banners/Download_app_on_homepage.png" alt="Home Page Ad">
</a>

<!-- <script>
  (function() {
    'use strict';

    function initParallax() {
      const mockups = document.querySelectorAll('.mockup.scroll-animated');

      function updateParallax() {
        mockups.forEach((mockup) => {
          if (mockup.classList.contains('animated') && !mockup.matches(':hover')) {
            return;
          }

          const rect = mockup.getBoundingClientRect();
          const windowHeight = window.innerHeight;
          const elementTop = rect.top;
          const elementCenter = elementTop + rect.height / 2;
          const windowCenter = windowHeight / 2;

          const distance = elementCenter - windowCenter;
          const parallaxOffset = distance * 0.03;

          if (rect.top < windowHeight && rect.bottom > 0 && mockup.classList.contains('scroll-animated') && !mockup.matches(':hover')) {
            mockup.style.transform = `translate(0, ${parallaxOffset}px) scale(1)`;
          }
        });
      }

      let ticking = false;
      window.addEventListener('scroll', function() {
        if (!ticking) {
          window.requestAnimationFrame(function() {
            updateParallax();
            ticking = false;
          });
          ticking = true;
        }
      }, {
        passive: true
      });
    }

    function initScrollAnimations() {
      const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
      };

      const observer = new IntersectionObserver(function(entries) {
        entries.forEach((entry, index) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('scroll-animated');

            setTimeout(() => {
              entry.target.classList.add('animated');
            }, 900);

            observer.unobserve(entry.target);
          }
        });
      }, observerOptions);

      document.querySelectorAll('.mockup').forEach(mockup => {
        observer.observe(mockup);
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        initParallax();
        initScrollAnimations();
      });
    } else {
      initParallax();
      initScrollAnimations();
    }

    window.addEventListener('load', function() {
      document.body.style.opacity = '1';
    });
  })();
</script> -->