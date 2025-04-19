<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Analytics Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 2rem;
      transition: background 0.3s;
    }

    .dark-mode {
      background-color: #121212;
      color: white;
    }

    .dark-mode .stats-card,
    .dark-mode .mini-card,
    .dark-mode .main-card {
      background-color: #1e1e1e;
      color: white;
    }

    .main-card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.05);
      transition: background 0.3s, color 0.3s;
    }

    .top-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 20px;
    }

    .welcome-card, .chart-card, .book-cover-card {
      flex: 1;
      min-width: 250px;
    }

    .chart-card canvas, .book-cover-card img {
      width: 100%;
    }

    .mini-cards-wrapper {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 20px;
    }

    .mini-card {
      background: #f9f9f9;
      padding: 15px;
      border-radius: 10px;
      flex: 1;
      text-align: center;
      min-width: 120px;
      transition: all 0.4s ease;
    }

    .download-report-card button {
      padding: 8px 12px;
      font-weight: bold;
      cursor: pointer;
      border: none;
      background: #28a745;
      color: white;
      border-radius: 6px;
    }

    .stats-cards {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 30px;
    }

    .stats-card {
      flex: 1;
      background: #f9f9f9;
      padding: 20px;
      border-radius: 12px;
      text-align: center;
      min-width: 200px;
      transition: all 0.4s ease;
    }

    .long-card {
      flex: 2;
    }

    .card-icon {
      font-size: 30px;
      margin-bottom: 10px;
    }

    /* Animations */
    .welcome-card, .chart-card, .book-cover-card, .mini-card, .stats-card {
      opacity: 0;
      transform: translateY(20px);
    }

    .loaded .welcome-card,
    .loaded .chart-card,
    .loaded .book-cover-card,
    .loaded .mini-card,
    .loaded .stats-card {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.8s ease;
    }

    .stats-card:nth-child(1) { transition-delay: 0.2s; }
    .stats-card:nth-child(2) { transition-delay: 0.4s; }
    .stats-card:nth-child(3) { transition-delay: 0.6s; }
    .stats-card:nth-child(4) { transition-delay: 0.8s; }

    .dark-mode-toggle {
      position: fixed;
      top: 10px;
      right: 10px;
      background: #444;
      color: #fff;
      border: none;
      padding: 8px 12px;
      border-radius: 6px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <button id="toggleDark" class="dark-mode-toggle">Dark Mode</button>

  <div class="main-card">
    <h2>Analytics Dashboard</h2>

    <div class="top-row">
      <div class="welcome-card">
        <h3>Welcome back, User!</h3>
        <p>Here's a quick overview of your stats</p>
      </div>
      <div class="chart-card">
        <canvas id="doughnutChart"></canvas>
      </div>
      <div class="book-cover-card">
        <img id="bookCover" src="Screenshot 2025-04-19 at 11.43.59.png" alt="Most Viewed Book Cover">
      </div>
      <div class="mini-cards-wrapper">
        <div class="mini-card">
          <h4>Profile Views</h4>
          <p><span class="count" data-target="24200">0</span></p>
        </div>
        <div class="mini-card">
          <h4>Downloads</h4>
          <p><span class="count" data-target="870">0</span></p>
        </div>
        <div class="mini-card">
          <h4>Top Region</h4>
          <p>Gauteng</p>
        </div>
        <div class="mini-card download-report-card">
          <button onclick="downloadReport()">⬇ Download Report</button>
        </div>
      </div>
    </div>

    <div class="stats-cards">
      <div class="stats-card">
        <i class="card-icon">📈</i>
        <div class="card-title">Active Users</div>
        <div class="card-value"><span class="count" data-target="1524">0</span></div>
      </div>
      <div class="stats-card">
        <i class="card-icon">💵</i>
        <div class="card-title">Revenue</div>
        <div class="card-value"><span class="count" data-target="15423">0</span></div>
      </div>
      <div class="stats-card">
        <i class="card-icon">🔔</i>
        <div class="card-title">Notifications</div>
        <div class="card-value"><span class="count" data-target="57">0</span></div>
      </div>
      <div class="stats-card long-card">
        <canvas id="provinceTrafficChart"></canvas>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Dark Mode Toggle
    document.getElementById('toggleDark').addEventListener('click', () => {
      document.body.classList.toggle('dark-mode');
    });

    // Animate Count-Up
    function animateCount(el, target) {
      let current = 0;
      const speed = 30;
      const step = Math.ceil(target / speed);

      function update() {
        current += step;
        if (current >= target) {
          el.textContent = target.toLocaleString();
        } else {
          el.textContent = current.toLocaleString();
          requestAnimationFrame(update);
        }
      }

      update();
    }

    // Start animation after load
    window.addEventListener('load', () => {
      document.body.classList.add('loaded');
      document.querySelectorAll('.count').forEach(el => {
        const target = +el.getAttribute('data-target');
        animateCount(el, target);
      });
    });

    // Doughnut Chart
    const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(doughnutCtx, {
      type: 'doughnut',
      data: {
        labels: ['Views', 'Orders', 'Favorites', 'Shares'],
        datasets: [{
          label: 'Interaction Metrics',
          data: [500, 200, 150, 50],
          backgroundColor: [ "rgba(0,255,255,0.7)",
            "rgba(0,255,100,0.7)",
            "rgba(255,0,255,0.7)",
            "rgba(255,255,0,0.7)"],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        animation: {
          animateScale: true,
          animateRotate: true
        },
        plugins: {
          legend: { position: 'top' },
          tooltip: { enabled: true }
        }
      }
    });

    // Bar Chart
    const barCtx = document.getElementById('provinceTrafficChart').getContext('2d');
    new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: ['Gauteng', 'KZN', 'Western Cape', 'Eastern Cape', 'Limpopo', 'Free State', 'Mpumalanga', 'North West', 'Northern Cape'],
        datasets: [{
          label: 'Traffic by Province',
          data: [1800, 1200, 950, 750, 500, 430, 400, 380, 300],
          backgroundColor: 'rgba(40, 167, 69, 0.3)',
          borderColor: '#28a745',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        animation: {
          duration: 1500,
          easing: 'easeOutBounce'
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    // Download Report Button
    function downloadReport() {
      const button = document.querySelector('.download-report-card button');
      button.textContent = 'Downloading...';
      button.disabled = true;
      setTimeout(() => {
        button.textContent = '⬇ Download Report';
        button.disabled = false;
        alert("Report downloaded!");
      }, 1500);
    }
  </script>
</body>
</html>
