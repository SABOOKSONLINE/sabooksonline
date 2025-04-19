<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="styles.css"> -->
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
  border-radius: 1px;
  padding: 20px;
  box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
  max-width: 2000px;
  margin: 10px auto;
  transition: all 0.3s ease;
}

.main-card:hover {
  box-shadow: 0 0 40px rgba(0, 0, 0, 0.15);
}

.top-row {
  display: flex;
  gap: 20px;
  justify-content: space-between;
  margin-bottom: 40px;
}

.welcome-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  flex: 1;
  max-width: 30%;
}

.welcome-card h3 {
  font-size: 1.5rem;
  color: #212529;
}

.welcome-card p {
  font-size: 1rem;
  color: #6c757d;
}

.chart-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  flex: 1;
  max-width: 30%;
}

.doughnut-chart-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.book-cover-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  flex: 1;
  max-width: 20%;
}

.book-cover-card img {
  width: 100%;
  border-radius: 10px;
}

.book-info {
  text-align: center;
  margin-top: 15px;
}

.book-info h4 {
  font-size: 1.2rem;
  color: #212529;
}

.book-info p {
  font-size: 1rem;
  color: #6c757d;
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

</style>
<<body>
    <button id="toggleDark" class="dark-mode-toggle">Dark Mode</button>

    <div class="main-card">
        <h2>Analytics Dashboard</h2>

        <!-- Top Row: Welcome, Doughnut Chart, and Most Viewed Book -->

         <div class="welcome-card">
                <h3>Profile views</h3>
                <p>24200</p>
          </div>
            
            <br>
        <div class="top-row">
            <div class="welcome-card">
                <h3>Welcome back, User!</h3>
                <p>Here's a quick overview of your stats</p>
            </div>
            <div class="chart-card doughnut-chart-card">
                <canvas id="doughnutChart"></canvas>
            </div>
            <div class="book-cover-card">
                <img id="bookCover" src="Screenshot 2025-04-19 at 11.43.59.png" alt="Most Viewed Book Cover">
               
            </div>
            <div class="welcome-card">
                <h3>Profile views</h3>
                <p>24200</p>
            </div>
             <div class="chart-card doughnut-chart-card">
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>

        <!-- Stats Cards -->
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="script.js"></script> -->
</body>


<script>
// Toggle Dark Mode
const toggleButton = document.getElementById('toggleDark');
toggleButton.addEventListener('click', () => {
  document.body.classList.toggle('dark-mode');
});

// Doughnut Chart Data
const doughnutChartData = {
  labels: ['Views', 'Orders', 'Favorites', 'Shares'],
  datasets: [{
    label: 'Interaction Metrics',
    data: [500, 200, 150, 50], // Example data
    backgroundColor: ['#ff9e9e', '#ffcc00', '#2e8b57', '#4c99f9'],
    borderWidth: 1
  }]
};

const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
new Chart(doughnutCtx, {
  type: 'doughnut',
  data: doughnutChartData,
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'top' },
      tooltip: { enabled: true }
    }
  }
});

// Display Book Cover & Stats
const bookCover = document.getElementById('bookCover');
const bookTitle = document.getElementById('bookTitle');
const bookViews = document.getElementById('bookViews');

// Sample book data
const mostViewedBook = {
  title: 'The Great Adventure',
  coverUrl: 'Screenshot 2025-04-19 at 11.43.59.png',
  views: 1245
};

bookCover.src = mostViewedBook.coverUrl;
// bookTitle.textContent = mostViewedBook.title;
// bookViews.textContent = `${mostViewedBook.views} Views`;

// Toast message example
const toast = document.getElementById('toast');
setTimeout(() => toast.classList.add('show'), 1000);
setTimeout(() => toast.classList.remove('show'), 5000);

</script>
</html>
