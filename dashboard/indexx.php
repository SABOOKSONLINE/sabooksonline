<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SA Books Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #f8fafc;
      font-family: 'Segoe UI', sans-serif;
      color: #2d2d2d;
    }

    .header-title {
      font-weight: 600;
      margin-bottom: 20px;
      font-size: 1.8rem;
    }

    .stat-card {
      border-radius: 12px;
      padding: 20px;
      color: white;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.07);
      display: flex;
      flex-direction: column;
      align-items: start;
    }

    .stat-card i {
      font-size: 28px;
      margin-bottom: 10px;
    }

    .bg-books { background: linear-gradient(45deg, #1976d2, #2196f3); }
    .bg-events { background: linear-gradient(45deg, #388e3c, #4caf50); }
    .bg-services { background: linear-gradient(45deg, #fbc02d, #fdd835); color: #212121; }
    .bg-total { background: linear-gradient(45deg, #8e24aa, #ab47bc); }
    .bg-users { background: linear-gradient(45deg, #e53935, #ef5350); }
    .bg-time { background: linear-gradient(45deg, #3949ab, #5c6bc0); }

    .stat-value {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .chart-box {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
      margin-bottom: 20px;
    }

    .chart-box h5 {
      font-weight: 600;
      margin-bottom: 20px;
    }

    .list-group-item {
      display: flex;
      justify-content: space-between;
      border: none;
      border-bottom: 1px solid #eee;
      padding: 10px 0;
    }

    .nav-tabs .nav-link.active {
      font-weight: bold;
      border-color: #dee2e6 #dee2e6 #fff;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="header-title">📚 SA Books Online — Admin Dashboard</h2>

    <!-- Stats Grid -->
    <div class="row g-3 mb-4">
      <div class="col-md-2 col-6">
        <div class="stat-card bg-books">
          <i class="fas fa-book"></i>
          <span>Book Views</span>
          <div class="stat-value">13,201</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card bg-events">
          <i class="fas fa-calendar-alt"></i>
          <span>Event Views</span>
          <div class="stat-value">3,288</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card bg-services">
          <i class="fas fa-tools"></i>
          <span>Service Views</span>
          <div class="stat-value">2,145</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card bg-total">
          <i class="fas fa-chart-line"></i>
          <span>Total Views</span>
          <div class="stat-value">18,634</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card bg-users">
          <i class="fas fa-user-friends"></i>
          <span>Unique Users</span>
          <div class="stat-value">5,904</div>
        </div>
      </div>
      <div class="col-md-2 col-6">
        <div class="stat-card bg-time">
          <i class="fas fa-clock"></i>
          <span>Avg. Time</span>
          <div class="stat-value">5m 12s</div>
        </div>
      </div>
    </div>

    <!-- Chart Row -->
    <div class="row g-3">
      <div class="col-md-4">
        <div class="chart-box">
          <h5>Engagement Breakdown</h5>
          <canvas id="engagementChart"></canvas>
        </div>
      </div>
      <div class="col-md-4">
        <div class="chart-box">
          <h5>Popular Cities</h5>
          <canvas id="cityChart"></canvas>
        </div>
      </div>
      <div class="col-md-4">
        <div class="chart-box">
          <h5>Top Provinces</h5>
          <canvas id="provinceChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Tabs Row -->
    <div class="row g-3 mt-2">
      <div class="col-md-6">
        <div class="chart-box">
          <h5>Most Viewed Content</h5>
          <ul class="nav nav-tabs" id="tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#books">Books</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#events">Events</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#services">Services</a></li>
          </ul>
          <div class="tab-content pt-3">
            <div class="tab-pane fade show active" id="books">
              <ul class="list-group">
                <li class="list-group-item">Bloodline of Kemet <span>465 views</span></li>
                <li class="list-group-item">Mindful Hustle <span>379 views</span></li>
                <li class="list-group-item">Cape Town Noir <span>352 views</span></li>
              </ul>
            </div>
            <div class="tab-pane fade" id="events">
              <ul class="list-group">
                <li class="list-group-item">Poetry Night CT <span>120 views</span></li>
                <li class="list-group-item">Author Talk SA <span>99 views</span></li>
              </ul>
            </div>
            <div class="tab-pane fade" id="services">
              <ul class="list-group">
                <li class="list-group-item">Cover Design <span>89 views</span></li>
                <li class="list-group-item">Editing Services <span>72 views</span></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="chart-box">
          <h5>Hourly User Activity</h5>
          <canvas id="hourChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart Scripts -->
  <script>
    new Chart(document.getElementById("engagementChart"), {
      type: "doughnut",
      data: {
        labels: ["Books", "Events", "Services", "Profiles"],
        datasets: [{
          data: [13201, 3288, 2145, 3000],
          backgroundColor: ["#1976d2", "#43a047", "#fbc02d", "#3949ab"]
        }]
      }
    });

    new Chart(document.getElementById("cityChart"), {
      type: "bar",
      data: {
        labels: ["New York", "London", "Nairobi", "Sydney", "Tokyo"],
        datasets: [{
          label: "Views",
          data: [450, 410, 390, 355, 320],
          backgroundColor: "#43a047"
        }]
      }
    });

    new Chart(document.getElementById("provinceChart"), {
      type: "bar",
      data: {
        labels: ["Gauteng", "KZN", "Western Cape", "Eastern Cape", "Limpopo"],
        datasets: [{
          label: "Views",
          data: [3200, 2750, 2540, 2100, 1600],
          backgroundColor: "#1976d2"
        }]
      }
    });

    new Chart(document.getElementById("hourChart"), {
      type: "line",
      data: {
        labels: ["00h", "03h", "06h", "09h", "12h", "15h", "18h", "21h"],
        datasets: [{
          label: "Active Users",
          data: [120, 140, 230, 500, 900, 750, 600, 300],
          fill: true,
          backgroundColor: "rgba(25, 118, 210, 0.2)",
          borderColor: "#1976d2",
          tension: 0.4
        }]
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
