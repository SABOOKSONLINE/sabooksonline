<?php
// scroll-apple.php
?>

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    body {
      background: #fff;
      color: #111;
      overflow-x: hidden;
      scroll-behavior: smooth;
    }

    section {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 60px 20px;
    }

    h1 {
      font-size: 3rem;
      margin-bottom: 20px;
      font-weight: 700;
    }

    p {
      max-width: 600px;
      font-size: 1.2rem;
      line-height: 1.5;
      color: #444;
    }

    /* --- Scroll Animation --- */
    .scroll-animate {
      opacity: 0;
      transform: translateY(80px);
      transition: all 1s cubic-bezier(0.19, 1, 0.22, 1);
    }

    .scroll-animate.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* --- Mockup Images --- */
    .mockups {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 40px;
    }

    .mockup {
      width: 220px;
      height: 450px;
      background: #f5f5f7;
      border-radius: 30px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .mockup img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .mockup:hover {
      transform: translateY(-10px);
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }

    footer {
      padding: 60px 20px;
      background: #f9f9f9;
      text-align: center;
      font-size: 0.9rem;
      color: #666;
    }
  </style>

  <section class="scroll-animate">
    <h1>Read. Listen. Discover.</h1>
    <p>All in one experience. Browse books, audiobooks, and personalized collections seamlessly — just like Apple Books.</p>
  </section>

  <section>
    <div class="mockups scroll-animate">
      <div class="mockup"><img src="https://sabooksonline.co.za/cms-data/mobile/home.png" alt="Book 1" /></div>
            <div class="mockup"><img src="https://sabooksonline.co.za/cms-data/mobile/education.png" alt="Book 1" /></div>
            <div class="mockup"><img src="https://sabooksonline.co.za/cms-data/mobile/library.png" alt="Book 1" /></div>
            <div class="mockup"><img src="https://sabooksonline.co.za/cms-data/mobile/media.png" alt="Book 1" /></div>
    </div>
  </section>

  <section class="scroll-animate">
    <h1>Beautifully Designed</h1>
    <p>Bring your reading experience to life with minimal design and smooth animations that glide in as you scroll.</p>
  </section>


  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const scrollElements = document.querySelectorAll(".scroll-animate");

      const elementInView = (el, offset = 100) => {
        const elementTop = el.getBoundingClientRect().top;
        return elementTop <= (window.innerHeight - offset);
      };

      const displayScrollElement = (element) => {
        element.classList.add("visible");
      };

      const hideScrollElement = (element) => {
        element.classList.remove("visible");
      };

      const handleScrollAnimation = () => {
        scrollElements.forEach((el, index) => {
          if (elementInView(el)) {
            setTimeout(() => displayScrollElement(el), index * 150); // stagger effect
          } else {
            hideScrollElement(el);
          }
        });
      };

      window.addEventListener("scroll", handleScrollAnimation);
      handleScrollAnimation(); // Run on load
    });
  </script>
