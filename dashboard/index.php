<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Analytics Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@1.2.0/dist/chartjs-chart-matrix.min.js"></script>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
     body {
      background: linear-gradient(135deg, #f8f9fa, #ffffff);
      color: #212529;
      font-family: 'Poppins', sans-serif;
      animation: pulseBg 8s infinite alternate;
    }

    @keyframes pulseBg {
      0% { background-position: 0% 50%; }
      100% { background-position: 100% 50%; }
    }

    .main-card {
      background: #fff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
      transition: 0.3s ease-in-out;
    }

    .stats-card {
      background: linear-gradient(135deg, #ffffff, #f1f1f1);
      border-radius: 20px;
      padding: 25px;
      height: 180px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.07);
      transition: transform 0.3s ease, opacity 0.5s ease;
      opacity: 0;
      transform: translateY(30px);
      animation: appear 0.8s forwards;
    }

    .stats-card:nth-child(1) { animation-delay: 0.1s; }
    .stats-card:nth-child(2) { animation-delay: 0.2s; }
    .stats-card:nth-child(3) { animation-delay: 0.3s; }
    .stats-card:nth-child(4) { animation-delay: 0.4s; }
    .stats-card:nth-child(5) { animation-delay: 0.5s; }

    @keyframes appear {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card-icon {
      font-size: 1.5rem;
      color: #28a745;
    }

    .card-title {
      font-size: 1rem;
      color: #6c757d;
      margin-top: 5px;
    }

    .card-value {
      font-size: 1.6rem;
      font-weight: 700;
      color: #212529;
    }

    .blurred {
      filter: blur(5px);
      pointer-events: none;
    }

    .tooltip-msg {
      font-size: 0.9rem;
      color: #198754;
      margin-top: 8px;
    }

    .chart-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      margin-top: 30px;
      animation: fadeInUp 0.7s ease both;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    canvas {
      max-height: 300px;
    }

    .stats-card {
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}
.stats-card {
  background: #e0e0e0;
  box-shadow: 8px 8px 16px #bebebe, -8px -8px 16px #ffffff;
}

  </style>
</head>
<body>

<div class="container py-5">
  <!-- Welcome Card -->
  <div class="main-card mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Dashboard</h2>
      <button class="btn btn-outline-light btn-sm" id="toggleUserType">Switch User Type</button>
    </div>
  </div>

  <!-- Stat Cards -->
  <div class="row g-4">
    <!-- Cards inserted here -->
  </div>

  <!-- Charts -->
  <div class="row">
    <div class="col-md-6">
      <div class="chart-card">
        <h5 class="mb-3">Income Over Time</h5>
        <canvas id="incomeChart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="chart-card">
        <h5 class="mb-3">Top Performing Books</h5>
        <canvas id="bookChart"></canvas>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/countup.js"></script>

<script>
  const userType = { premium: false };

  const dummyData = [
    { title: "Welcome Back", value: "Kganya Maleka", icon: "fa-hand-wave", msg: "Upgrade for insights" },
    { title: "Net Income", value: "R 85,000", icon: "fa-money-bill", msg: "Unlock full income breakdown" },
    { title: "Transactions", value: "328", icon: "fa-arrows-rotate", msg: "View exact transaction count" },
    { title: "Total Customers", value: "420", icon: "fa-users", msg: "See your customer reach" },
    { title: "Pending Orders", value: "17", icon: "fa-hourglass-half", msg: "Track pending book orders" },
    { title: "Book Views", value: "14,200", icon: "fa-book", msg: "See how many people viewed your books" },
    { title: "Unique Book Users", value: "3,400", icon: "fa-user-check", msg: "Get real visitor numbers" },
    { title: "Event Views", value: "1,250", icon: "fa-calendar-days", msg: "Check event engagement" },
    { title: "Unique Event Users", value: "850", icon: "fa-user-group", msg: "Know who attends events" },
  ];

  const renderCards = () => {
    const row = document.querySelector(".row.g-4");
    row.innerHTML = "";

    dummyData.forEach(stat => {
      row.innerHTML += `
        <div class="col-md-3">
          <div class="stats-card ${!userType.premium ? 'blurred' : ''}">
            <div class="card-icon"><i class="fa-solid ${stat.icon}"></i></div>
            <div class="card-title">${stat.title}</div>
            <div class="card-value">${stat.value}</div>
            ${!userType.premium ? `<div class="tooltip-msg">${stat.msg}</div>` : ''}
          </div>
        </div>
      `;
    });
  }

  document.getElementById('toggleUserType').addEventListener('click', () => {
    userType.premium = !userType.premium;
    renderCards();
    document.getElementById('toggleUserType').innerText = userType.premium ? "Switch to Free User" : "Switch to Premium";
  });

  renderCards();

  // Chart.js Setup
  const incomeChart = new Chart(document.getElementById('incomeChart'), {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
      datasets: [{
        label: 'Income',
        data: [12000, 15000, 18000, 14000, 20000],
        borderColor: '#9eff00',
        backgroundColor: 'rgba(158, 255, 0, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      scales: {
        x: {
          ticks: { color: '#ccc' },
          grid: { color: '#2a2a2a' }
        },
        y: {
          ticks: { color: '#ccc' },
          grid: { color: '#2a2a2a' }
        }
      },
      plugins: {
        legend: { labels: { color: '#fff' } }
      }
    }
  });

  const bookChart = new Chart(document.getElementById('bookChart'), {
    type: 'bar',
    data: {
      labels: ['Book A', 'Book B', 'Book C', 'Book D'],
      datasets: [{
        label: 'Views',
        data: [3000, 2500, 4000, 1500],
        backgroundColor: ['#9eff00', '#00ffc8', '#ff9f00', '#ff3e96']
      }]
    },
    options: {
      scales: {
        x: {
          ticks: { color: '#ccc' },
          grid: { color: '#2a2a2a' }
        },
        y: {
          ticks: { color: '#ccc' },
          grid: { color: '#2a2a2a' }
        }
      },
      plugins: {
        legend: { labels: { color: '#fff' } }
      }
    }
  });

  
  
</script>

</body>
</html>
