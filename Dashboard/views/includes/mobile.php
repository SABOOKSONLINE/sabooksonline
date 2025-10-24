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
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    body {
      background: #fff;
      color: #111;
      overflow-x: hidden;
    }

    sectionn {
      padding: 60px 20px 20px 20px; /* reduced bottom padding */
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    h1 {
      margin-top: 0;
      font-size: 2.5rem;
      margin-bottom: 16px;
      font-weight: 700;
      line-height: 1.2;
    }

    p {
      max-width: 600px;
      font-size: 1.1rem;
      line-height: 1.5;
      color: #555;
      margin-bottom: 24px; /* space before buttons */
    }

    /* --- Download Buttons --- */
    .download-buttons {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 20px; /* space before images */
    }

    .download-buttons a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 12px 20px;
      border-radius: 12px;
      text-decoration: none;
      color: #000;
      font-weight: 600;
      font-size: 0.95rem;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .download-buttons a.ios {
      background: #fff;
    }

    .download-buttons a.android {
      background: #fff;
    }

  

    /* --- Mockup Images --- */
    .mockups {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 60px;
      padding: 20px 20px 40px 20px;
    }

    .mockup {
      width: 230px;
      background: #f5f5f7;
      border-radius: 25px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .mockup img {
      width: 100%;
      height: 100%;
      object-fit: contain; /* shows full image without cutting */
      background: #f5f5f7;
    }

    .mockup:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

  <sectionn>
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
  </sectionn>

  <section>
    <div class="mockups">
      <div class="mockup"><img src="https://sabooksonline.co.za/cms-data/mobile/home.png" alt="Book 1" /></div>
      <div class="mockup"><img src="https://sabooksonline.co.za/cms-data/mobile/education.png" alt="Book 2" /></div>
      <div class="mockup"><img src="https://sabooksonline.co.za/cms-data/mobile/library.png" alt="Book 3" /></div>
      <div class="mockup"><img src="https://sabooksonline.co.za/cms-data/mobile/media.png" alt="Book 4" /></div>
    </div>
  </section>

</body>
</html>
