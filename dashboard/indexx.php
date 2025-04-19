<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<style>

 /* Global Styles */
body {
  background: linear-gradient(135deg, #f8f9fa, #ffffff);
  color: #212529;
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  transition: background 0.3s ease, color 0.3s ease;
}

body.dark-mode {
  background: linear-gradient(135deg, #1c1c1e, #2c2c2e);
  color: #f1f1f1;
}

.main-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
  max-width: 1100px;
  margin: 40px auto;
  transition: all 0.3s ease;
}

.main-card:hover {
  box-shadow: 0 0 40px rgba(0, 0, 0, 0.15);
}

.stats-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}

.stats-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  padding: 30px;
  height: 200px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  opacity: 0;
  transform: translateY(30px);
  animation: appear 0.8s forwards;
}

.stats-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.stats-card:nth-child(1) { animation-delay: 0.1s; }
.stats-card:nth-child(2) { animation-delay: 0.2s; }
.stats-card:nth-child(3) { animation-delay: 0.3s; }
.stats-card:nth-child(4) { animation-delay: 0.4s; }

@keyframes appear {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.card-icon {
  font-size: 2rem;
  color: #28a745;
  transition: transform 0.3s ease, color 0.3s ease;
}

.card-icon:hover {
  transform: scale(1.2);
  color: #218838;
}

.card-title {
  font-size: 1rem;
  color: #6c757d;
  margin-top: 5px;
}

.card-value {
  font-size: 1.8rem;
  font-weight: 700;
  color: #212529;
  margin-top: 10px;
  text-transform: uppercase;
}

.chart-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  margin-top: 40px;
  animation: fadeInUp 0.7s ease both;
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

canvas {
  max-height: 350px;
  width: 100%;
}

.toast {
  visibility: hidden;
  max-width: 50px;
  width: 250px;
  background-color: #198754;
  color: #fff;
  text-align: center;
  border-radius: 10px;
  padding: 8px;
  position: fixed;
  z-index: 1;
  left: 50%;
  top: 20px;
  transform: translateX(-50%);
  opacity: 0;
  transition: opacity 0.5s ease;
}

.toast.show {
  visibility: visible;
  opacity: 1;
}

.dark-mode-toggle {
  position: fixed;
  top: 20px;
  right: 20px;
  background: #28a745;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
}

.dark-mode-toggle:hover {
  background: #218838;
}



</style>
<<body>
    <button id="toggleDark" class="dark-mode-toggle">Dark Mode</button>

    <div class="main-card">
        <h2>Dashboard</h2>
        <div class="stats-cards">
            <div class="stats-card" data-card="1">
                <i class="card-icon">📈</i>
                <div class="card-title">Active Users</div>
                <div class="card-value">1,524</div>
            </div>
            <div class="stats-card" data-card="2">
                <i class="card-icon">💵</i>
                <div class="card-title">Revenue</div>
                <div class="card-value">₤15,423</div>
            </div>
            <div class="stats-card" data-card="3">
                <i class="card-icon">🔔</i>
                <div class="card-title">Notifications</div>
                <div class="card-value">57</div>
            </div>
            <div class="stats-card" data-card="4">
                <i class="card-icon">💬</i>
                <div class="card-title">Messages</div>
                <div class="card-value">98</div>
            </div>
        </div>

        <!-- Toast message -->
        <div id="toast" class="toast">Someone just viewed your profile!</div>
    </div>

    <!-- Chart Area -->
    <div class="chart-card">
        <canvas id="myChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="script.js"></script>
</body>


<script>
  // Dark mode toggle functionality
document.getElementById("toggleDark").onclick = () => {
  document.body.classList.toggle("dark-mode");
};

// Simulate live number count-up animation
function animateValue(el, start, end, duration) {
  let range = end - start;
  let startTime = null;

  function step(timestamp) {
    if (!startTime) startTime = timestamp;
    let progress = timestamp - startTime;
    let value = Math.min(start + Math.floor((progress / duration) * range), end);
    el.textContent = value;
    if (value < end) requestAnimationFrame(step);
  }

  requestAnimationFrame(step);
}

// Trigger toast message after page load
setTimeout(() => {
  document.getElementById("toast").classList.add("show");
}, 2000);

// Initialize the chart with random data
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
    datasets: [{
      label: 'Monthly Revenue',
      data: [100, 150, 120, 180, 200],
      borderColor: '#28a745',
      backgroundColor: 'rgba(40, 167, 69, 0.2)',
      borderWidth: 2,
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: true,
        position: 'top'
      }
    }
  }
});

// Number animation on load
animateValue(document.querySelector(".stats-card[data-card='1'] .card-value"), 0, 1524, 1000);
animateValue(document.querySelector(".stats-card[data-card='2'] .card-value"), 0, 15423, 1000);
animateValue(document.querySelector(".stats-card[data-card='3'] .card-value"), 0, 57, 1000);
animateValue(document.querySelector(".stats-card[data-card='4'] .card-value"), 0, 98, 1000);

</script>
</html>
